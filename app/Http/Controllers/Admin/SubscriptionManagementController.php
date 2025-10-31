<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SubscriptionRequest;
use App\Models\ClientDevice;
use App\Models\Client;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SubscriptionManagementController extends Controller
{
  /**
   * Display a listing of the subscriptions.
   */
  public function index(Request $request)
  {
    $query = SubscriptionRequest::with([
      'client',
      'payment',
      'devices' => function ($q) {
        $q->where('status', 'active');
      }
    ]);

    // Filter by status
    if ($request->has('status') && $request->status !== 'all' && $request->status !== '') {
      $query->where('status', $request->status);
    } else if ($request->has('status') && $request->status === 'all') {
      $query->where([]);
    }

    // Filter by client
    if ($request->has('client_search') && $request->client_search !== '') {
      $query->whereHas('client', function ($q) use ($request) {
        $q->where('name', 'like', '%' . $request->client_search . '%')
          ->orWhere('email', 'like', '%' . $request->client_search . '%')
          ->orWhere('phone', 'like', '%' . $request->client_search . '%');
      });
    }

    // Filter by payment status
    if ($request->has('payment_status') && $request->payment_status !== '') {
      $query->whereHas('payment', function ($q) use ($request) {
        $q->where('status', $request->payment_status);
      });
    }

    // Sort by date
    $sortOrder = $request->get('sort', 'desc');
    $query->orderBy('created_at', $sortOrder);

    $subscriptions = $query->paginate(15);

    // Statistics
    $stats = [
      'total' => SubscriptionRequest::count(),
      'active' => SubscriptionRequest::where('status', 'active')->count(),
      'pending' => SubscriptionRequest::where('status', 'pending')->count(),
      'expired' => SubscriptionRequest::where('status', 'expired')->count(),
      'suspended' => SubscriptionRequest::where('status', 'suspended')->count(),
      'total_revenue' => Payment::where('status', 'verified')->sum('amount'),
      'pending_payments' => Payment::where('status', 'pending_verification')->count(),
    ];

    return view('admin.subscriptions.index', compact('subscriptions', 'stats'));
  }

  /**
   * Display the specified subscription.
   */
  public function show(SubscriptionRequest $subscription)
  {
    $subscription->load([
      'client',
      'payment',
      'devices' => function ($q) {
        $q->orderBy('created_at', 'desc');
      }
    ]);

    // Calculate subscription timeline
    $timeline = [];
    if ($subscription->activated_at) {
      $timeline['activated'] = $subscription->activated_at;
      $timeline['expires'] = Carbon::parse($subscription->activated_at)->addYear();
      $timeline['days_remaining'] = Carbon::now()->diffInDays(Carbon::parse($subscription->activated_at)->addYear(), false);
    }

    // Device statistics
    $device_stats = [
      'total' => $subscription->devices()->count(),
      'active' => $subscription->devices()->where('status', 'active')->count(),
      'suspended' => $subscription->devices()->where('status', 'inactive')->count(),
      'by_type' => $subscription->devices()
        ->select('device_type', DB::raw('count(*) as count'))
        ->groupBy('device_type')
        ->get()
    ];

    return view('admin.subscriptions.show', compact('subscription', 'timeline', 'device_stats'));
  }

  /**
   * Activate subscription with custom activation date
   */
  public function activate(Request $request, SubscriptionRequest $subscription)
  {
    $request->validate([
      'activation_date' => 'nullable|date',
      'notes' => 'nullable|string|max:500'
    ]);

    $activationDate = $request->activation_date ?
      Carbon::parse($request->activation_date) :
      Carbon::now();

    try {
      DB::beginTransaction();

      $subscription->update([
        'status' => 'active',
        'activated_at' => $activationDate,
        'expires_at' => $activationDate->copy()->addYear(),
        'admin_notes' => $request->notes
      ]);

      // Update payment status if exists
      if ($subscription->payment) {
        $subscription->payment->update([
          'status' => 'verified',
          'verified_at' => now(),
          'verified_by' => auth()->id()
        ]);
      }

      // Activate all associated devices
      $subscription->devices()->update([
        'is_active' => true,
        'activated_at' => $activationDate
      ]);

      DB::commit();

      return response()->json([
        'success' => true,
        'message' => 'تم تفعيل الاشتراك بنجاح',
        'expires_at' => $subscription->expires_at->format('Y-m-d'),
        'status' => 'active'
      ]);
    } catch (\Exception $e) {
      DB::rollback();
      return response()->json([
        'success' => false,
        'message' => 'حدث خطأ أثناء تفعيل الاشتراك: ' . $e->getMessage()
      ], 500);
    }
  }

  /**
   * Suspend subscription
   */
  public function suspend(Request $request, SubscriptionRequest $subscription)
  {
    $request->validate([
      'reason' => 'required|string|max:500'
    ]);

    try {
      DB::beginTransaction();

      $subscription->update([
        'status' => 'suspended',
        'suspended_at' => now(),
        'suspension_reason' => $request->reason
      ]);

      // Suspend all devices
      $subscription->devices()->update([
        'is_active' => false,
        'suspended_at' => now()
      ]);

      DB::commit();

      return response()->json([
        'success' => true,
        'message' => 'تم تعليق الاشتراك بنجاح'
      ]);
    } catch (\Exception $e) {
      DB::rollback();
      return response()->json([
        'success' => false,
        'message' => 'حدث خطأ أثناء تعليق الاشتراك: ' . $e->getMessage()
      ], 500);
    }
  }

  /**
   * Renew subscription
   */
  public function renew(Request $request, SubscriptionRequest $subscription)
  {
    $request->validate([
      'renewal_period' => 'required|in:1_year,6_months,3_months',
      'payment_amount' => 'required|numeric|min:0'
    ]);

    try {
      DB::beginTransaction();

      // Calculate new expiry date
      $currentExpiry = $subscription->expires_at ?? now();
      $newExpiry = match ($request->renewal_period) {
        '1_year' => Carbon::parse($currentExpiry)->addYear(),
        '6_months' => Carbon::parse($currentExpiry)->addMonths(6),
        '3_months' => Carbon::parse($currentExpiry)->addMonths(3),
      };

      $subscription->update([
        'status' => 'active',
        'expires_at' => $newExpiry,
        'renewed_at' => now()
      ]);

      // Create renewal payment record
      Payment::create([
        'subscription_request_id' => $subscription->id,
        'amount' => $request->payment_amount,
        'status' => 'verified',
        'payment_method' => 'admin_renewal',
        'verified_at' => now(),
        'verified_by' => auth()->id(),
        'notes' => 'تجديد اشتراك من لوحة الإدارة'
      ]);

      DB::commit();

      return response()->json([
        'success' => true,
        'message' => 'تم تجديد الاشتراك بنجاح',
        'new_expiry' => $newExpiry->format('Y-m-d')
      ]);
    } catch (\Exception $e) {
      DB::rollback();
      return response()->json([
        'success' => false,
        'message' => 'حدث خطأ أثناء تجديد الاشتراك: ' . $e->getMessage()
      ], 500);
    }
  }

  /**
   * Get devices for specific subscription
   */
  public function devices(SubscriptionRequest $subscription)
  {
    $devices = $subscription->devices()
      ->orderBy('created_at', 'desc')
      ->get();

    return view('admin.subscriptions.devices', compact('subscription', 'devices'));
  }

  /**
   * Add new device to subscription
   */
  public function addDevice(Request $request, SubscriptionRequest $subscription)
  {
    $request->validate([
      'device_name' => 'required|string|max:255',
      'device_type' => 'required|in:iPhone,iPad,Mac,Apple TV,Apple Watch',
      'serial_number' => 'required|string|max:255|unique:client_devices,serial_number',
      'model' => 'nullable|string|max:255',
      'ios_version' => 'nullable|string|max:50'
    ]);

    try {
      $device = ClientDevice::create([
        'client_id' => $subscription->client_id,
        'subscription_request_id' => $subscription->id,
        'device_name' => $request->device_name,
        'device_type' => $request->device_type,
        'serial_number' => $request->serial_number,
        'model' => $request->model,
        'ios_version' => $request->ios_version,
        'is_active' => $subscription->status === 'active',
        'activated_at' => $subscription->status === 'active' ? now() : null,
        'device_token' => bin2hex(random_bytes(16))
      ]);

      return response()->json([
        'success' => true,
        'message' => 'تم إضافة الجهاز بنجاح',
        'device' => $device
      ]);
    } catch (\Exception $e) {
      return response()->json([
        'success' => false,
        'message' => 'حدث خطأ أثناء إضافة الجهاز: ' . $e->getMessage()
      ], 500);
    }
  }

  /**
   * Update device
   */
  public function updateDevice(Request $request, SubscriptionRequest $subscription, ClientDevice $device)
  {
    $request->validate([
      'device_name' => 'required|string|max:255',
      'model' => 'nullable|string|max:255',
      'ios_version' => 'nullable|string|max:50'
    ]);

    try {
      $device->update([
        'device_name' => $request->device_name,
        'model' => $request->model,
        'ios_version' => $request->ios_version
      ]);

      return response()->json([
        'success' => true,
        'message' => 'تم تحديث الجهاز بنجاح'
      ]);
    } catch (\Exception $e) {
      return response()->json([
        'success' => false,
        'message' => 'حدث خطأ أثناء تحديث الجهاز: ' . $e->getMessage()
      ], 500);
    }
  }

  /**
   * Activate device
   */
  public function activateDevice(SubscriptionRequest $subscription, ClientDevice $device)
  {
    try {
      $device->update([
        'is_active' => true,
        'activated_at' => now(),
        'suspended_at' => null
      ]);

      return response()->json([
        'success' => true,
        'message' => 'تم تفعيل الجهاز بنجاح'
      ]);
    } catch (\Exception $e) {
      return response()->json([
        'success' => false,
        'message' => 'حدث خطأ أثناء تفعيل الجهاز: ' . $e->getMessage()
      ], 500);
    }
  }

  /**
   * Suspend device
   */
  public function suspendDevice(Request $request, SubscriptionRequest $subscription, ClientDevice $device)
  {
    try {
      $device->update([
        'is_active' => false,
        'suspended_at' => now()
      ]);

      return response()->json([
        'success' => true,
        'message' => 'تم تعليق الجهاز بنجاح'
      ]);
    } catch (\Exception $e) {
      return response()->json([
        'success' => false,
        'message' => 'حدث خطأ أثناء تعليق الجهاز: ' . $e->getMessage()
      ], 500);
    }
  }

  /**
   * Remove device from subscription
   */
  public function removeDevice(SubscriptionRequest $subscription, ClientDevice $device)
  {
    try {
      $device->delete();

      return response()->json([
        'success' => true,
        'message' => 'تم حذف الجهاز بنجاح'
      ]);
    } catch (\Exception $e) {
      return response()->json([
        'success' => false,
        'message' => 'حدث خطأ أثناء حذف الجهاز: ' . $e->getMessage()
      ], 500);
    }
  }

  /**
   * Show Subscription Edit Form
   */

  public function edit(Request $request, SubscriptionRequest $subscription)
  {
    return view('admin.subscriptions.edit', compact('subscription'));
  }

  /**
   * Update subscription details
   */
  public function update(Request $request, SubscriptionRequest $subscription)
  {
    $request->validate([
      'activation_date' => 'nullable|date',
      'expiry_date' => 'nullable|date|after:activation_date',
      'admin_notes' => 'nullable|string|max:1000'
    ]);

    try {
      $updateData = [
        'admin_notes' => $request->admin_notes
      ];

      if ($request->activation_date) {
        $updateData['activated_at'] = $request->activation_date;

        if ($request->expiry_date) {
          $updateData['expires_at'] = $request->expiry_date;
        } else {
          // Auto calculate expiry (1 year from activation)
          $updateData['expires_at'] = Carbon::parse($request->activation_date)->addYear();
        }
      }

      $subscription->update($updateData);

      return response()->json([
        'success' => true,
        'message' => 'تم تحديث الاشتراك بنجاح'
      ]);
    } catch (\Exception $e) {
      return response()->json([
        'success' => false,
        'message' => 'حدث خطأ أثناء تحديث الاشتراك: ' . $e->getMessage()
      ], 500);
    }
  }

  /**
   * Delete subscription
   */
  public function destroy(SubscriptionRequest $subscription)
  {
    try {
      DB::beginTransaction();

      // Delete associated devices
      $subscription->devices()->delete();

      // Delete associated payments
      $subscription->payment?->delete();

      // Delete subscription
      $subscription->delete();

      DB::commit();

      return response()->json([
        'success' => true,
        'message' => 'تم حذف الاشتراك وجميع البيانات المرتبطة بنجاح'
      ]);
    } catch (\Exception $e) {
      DB::rollback();
      return response()->json([
        'success' => false,
        'message' => 'حدث خطأ أثناء حذف الاشتراك: ' . $e->getMessage()
      ], 500);
    }
  }
}
