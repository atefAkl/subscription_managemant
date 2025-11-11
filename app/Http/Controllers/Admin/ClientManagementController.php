<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ClientRequest;
use App\Models\User;
use App\Models\ClientProfile;
use App\Models\ClientDevice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class ClientManagementController extends Controller
{
  public function __construct()
  {
    // Middleware handled by routes
  }

  /**
   * Display a listing of client users
   */
  public function index(Request $request)
  {
    $query = User::where('role', 'client')->with('clientProfile');

    // Filter by subscription status
    if ($request->has('status') && $request->status !== '') {
      $query->whereHas('clientProfile', function ($q) use ($request) {
        $q->where('subscription_status', $request->status);
      });
    }

    // Filter by subscription type
    if ($request->has('type') && $request->type !== '') {
      $query->whereHas('clientProfile', function ($q) use ($request) {
        $q->where('subscription_type', $request->type);
      });
    }

    // Filter by payment status
    if ($request->has('payment') && $request->payment !== '') {
      $query->whereHas('clientProfile', function ($q) use ($request) {
        $q->where('payment_status', $request->payment);
      });
    }

    // Search by name or email
    if ($request->has('search') && $request->search !== '') {
      $query->where(function ($q) use ($request) {
        $q->where('name', 'like', '%' . $request->search . '%')
          ->orWhere('email', 'like', '%' . $request->search . '%');
      });
    }

    $clients = $query->orderBy('created_at', 'desc')->paginate(15);

    $stats = [
      'total_clients' => User::where('role', 'client')->count(),
      'active_clients' => User::where('role', 'client')
        ->whereHas('clientProfile', function ($q) {
          $q->where('subscription_status', 'active');
        })->count(),
      'new_clients' => User::where('role', 'client')
        ->where('created_at', '>=', now()->subDays(30))
        ->count(),
      'expiring_soon' => User::where('role', 'client')
        ->whereHas('clientProfile', function ($q) {
          $q->where('subscription_end_date', '<=', now()->addDays(7))
            ->where('subscription_end_date', '>', now());
        })->count(),
    ];

    return view('admin.clients.index', compact('clients', 'stats'));
  }

  /**
   * Show the form for creating a new client
   */
  public function create()
  {
    return view('admin.clients.create');
  }

  /**
   * Store a newly created client
   */
  public function store(Request $request)
  {


    $validated = $request->validate([
      'name' => 'required|string|max:255',
      'email' => 'required|string|email|max:255|unique:users',
      'password' => 'required|string|min:8|confirmed',
      'phone' => 'nullable|string|max:255',
      'address' => 'nullable|string|max:1000',
      'notes' => 'nullable|string|max:1000',
      // Client Profile fields
      'subscription_type' => 'required|in:basic,premium,enterprise',
      'subscription_status' => 'required|in:active,inactive,suspended,expired,trial',
      'subscription_start_date' => 'nullable|date',
      'subscription_end_date' => 'nullable|date|after:subscription_start_date',
      'device_limit' => 'required|integer|min:1|max:50',
      'payment_status' => 'required|in:paid,pending,overdue,failed',
      'billing_cycle' => 'required|in:monthly,quarterly,yearly',
      'client_notes' => 'nullable|string|max:1000',
    ]);

    $user = User::create([
      'name' => $validated['name'],
      'email' => $validated['email'],
      'password' => Hash::make($validated['password']),
      'role' => 'client',
      'phone' => $validated['phone'] ?? null,
      'address' => $validated['address'] ?? null,
      'notes' => $validated['notes'] ?? null,
    ]);

    // Create client profile
    ClientProfile::create([
      'user_id' => $user->id,
      'subscription_type' => $validated['subscription_type'],
      'subscription_status' => $validated['subscription_status'],
      'subscription_start_date' => $validated['subscription_start_date'] ?
        Carbon::parse($validated['subscription_start_date']) : null,
      'subscription_end_date' => $validated['subscription_end_date'] ?
        Carbon::parse($validated['subscription_end_date']) : null,
      'device_limit' => $validated['device_limit'],
      'payment_status' => $validated['payment_status'],
      'billing_cycle' => $validated['billing_cycle'],
      'client_notes' => $validated['client_notes'] ?? null,
    ]);

    return redirect()->route('admin.clients.index')
      ->with('success', 'تم إنشاء العميل بنجاح');
  }

  /**
   * Display the specified client
   */
  public function show(User $client)
  {
    // Ensure user is a client
    if (!$client->isClient()) {
      abort(404, 'العميل غير موجود');
    }

    $client->load(['clientProfile', 'clientDevices']);

    // Update devices count based on real devices
    if ($client->clientProfile) {
      $realDevicesCount = $client->clientDevices()->count();
      if ($client->clientProfile->devices_count !== $realDevicesCount) {
        $client->clientProfile->update(['devices_count' => $realDevicesCount]);
      }
    }

    // Get subscription requests
    $subscriptionRequests = \App\Models\SubscriptionRequest::where('user_id', $client->id)
      ->orderBy('created_at', 'desc')
      ->get();

    // Get active subscriptions (from clientProfile if exists, or create collection)
    $activeSubscriptions = collect();
    if ($client->clientProfile) {
      $activeSubscriptions->push($client->clientProfile);
    }

    $stats = [
      'subscription_days_left' => $client->clientProfile?->getRemainingDays(),
      'devices_available' => $client->clientProfile?->getAvailableDevices() ?? 0,
      'total_payments' => 0, // Simplified for now since payments table might not exist
      'last_payment' => null, // Simplified for now
    ];

    return view('admin.clients.show', compact('client', 'stats', 'subscriptionRequests', 'activeSubscriptions'));
  }

  /**
   * Show the form for editing the client
   */
  public function edit(User $client)
  {
    // Ensure user is a client
    if (!$client->isClient()) {
      abort(404, 'العميل غير موجود');
    }

    $client->load('clientProfile');
    return view('admin.clients.edit', compact('client'));
  }

  /**
   * Update the specified client
   */
  public function update(Request $request, User $client)
  {
    // Ensure user is a client
    if (!$client->isClient()) {
      abort(404, 'العميل غير موجود');
    }

    $validated = $request->validate([
      'name' => 'required|string|max:255',
      'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($client->id)],
      'password' => 'nullable|string|min:8|confirmed',
      'phone' => 'nullable|string|max:255',
      'address' => 'nullable|string|max:1000',
      'notes' => 'nullable|string|max:1000',
      // Client Profile fields
      'subscription_type' => 'required|in:basic,premium,enterprise',
      'subscription_status' => 'required|in:active,inactive,suspended,expired,trial',
      'subscription_start_date' => 'nullable|date',
      'subscription_end_date' => 'nullable|date|after:subscription_start_date',
      'device_limit' => 'required|integer|min:1|max:50',
      'devices_count' => 'required|integer|min:0',
      'payment_status' => 'required|in:paid,pending,overdue,failed',
      'billing_cycle' => 'required|in:monthly,quarterly,yearly',
      'client_notes' => 'nullable|string|max:1000',
    ]);

    $updateData = [
      'name' => $validated['name'],
      'email' => $validated['email'],
      'phone' => $validated['phone'] ?? null,
      'address' => $validated['address'] ?? null,
      'notes' => $validated['notes'] ?? null,
    ];

    if (!empty($validated['password'])) {
      $updateData['password'] = Hash::make($validated['password']);
    }

    $client->update($updateData);

    // Update client profile
    $client->clientProfile()->updateOrCreate(
      ['user_id' => $client->id],
      [
        'subscription_type' => $validated['subscription_type'],
        'subscription_status' => $validated['subscription_status'],
        'subscription_start_date' => $validated['subscription_start_date'] ?
          Carbon::parse($validated['subscription_start_date']) : null,
        'subscription_end_date' => $validated['subscription_end_date'] ?
          Carbon::parse($validated['subscription_end_date']) : null,
        'device_limit' => $validated['device_limit'],
        'devices_count' => $validated['devices_count'],
        'payment_status' => $validated['payment_status'],
        'billing_cycle' => $validated['billing_cycle'],
        'client_notes' => $validated['client_notes'] ?? null,
      ]
    );

    return redirect()->route('admin.clients.show', $client)
      ->with('success', 'تم تحديث بيانات العميل بنجاح');
  }

  /**
   * Remove the specified client
   */
  public function destroy(User $client)
  {
    // Ensure user is a client
    if (!$client->isClient()) {
      abort(404, 'العميل غير موجود');
    }

    $clientName = $client->name;
    $client->delete();

    return redirect()->route('admin.clients.index')
      ->with('success', 'تم حذف العميل "' . $clientName . '" بنجاح');
  }

  /**
   * Get client subscription activities
   */
  public function activities(User $client)
  {
    // Ensure user is a client
    if (!$client->isClient()) {
      return response()->json(['error' => 'العميل غير موجود'], 404);
    }

    $activities = collect();

    // Add basic activities
    $activities->push([
      'type' => 'account_created',
      'description' => 'إنشاء حساب العميل',
      'date' => $client->created_at->diffForHumans(),
      'status' => 'completed',
    ]);

    // Add profile creation
    if ($client->clientProfile) {
      $activities->push([
        'type' => 'profile_created',
        'description' => 'إنشاء بروفايل الاشتراك',
        'date' => $client->clientProfile->created_at->diffForHumans(),
        'status' => 'completed',
        'details' => [
          'subscription_type' => $client->clientProfile->getSubscriptionTypeText(),
          'device_limit' => $client->clientProfile->device_limit,
        ]
      ]);

      if ($client->clientProfile->updated_at != $client->clientProfile->created_at) {
        $activities->push([
          'type' => 'profile_updated',
          'description' => 'تحديث بيانات الاشتراك',
          'date' => $client->clientProfile->updated_at->diffForHumans(),
          'status' => 'completed',
        ]);
      }
    }

    // Add last login activity
    if ($client->last_login_at) {
      $activities->push([
        'type' => 'login',
        'description' => 'آخر تسجيل دخول',
        'date' => $client->last_login_at->diffForHumans(),
        'status' => 'completed',
      ]);
    }

    $activities = $activities->sortBy('date')->reverse()->take(10);

    return response()->json($activities);
  }

  /**
   * Get clients statistics for dashboard
   */
  public function statistics()
  {
    $stats = [
      'subscription_types' => [
        'basic' => User::where('role', 'client')
          ->whereHas('clientProfile', function ($q) {
            $q->where('subscription_type', 'basic');
          })->count(),
        'premium' => User::where('role', 'client')
          ->whereHas('clientProfile', function ($q) {
            $q->where('subscription_type', 'premium');
          })->count(),
        'enterprise' => User::where('role', 'client')
          ->whereHas('clientProfile', function ($q) {
            $q->where('subscription_type', 'enterprise');
          })->count(),
      ],
      'payment_status' => [
        'paid' => User::where('role', 'client')
          ->whereHas('clientProfile', function ($q) {
            $q->where('payment_status', 'paid');
          })->count(),
        'pending' => User::where('role', 'client')
          ->whereHas('clientProfile', function ($q) {
            $q->where('payment_status', 'pending');
          })->count(),
        'overdue' => User::where('role', 'client')
          ->whereHas('clientProfile', function ($q) {
            $q->where('payment_status', 'overdue');
          })->count(),
      ],
      'monthly_growth' => $this->getMonthlyGrowthData(),
      'recent_activities' => $this->getRecentActivities(),
    ];

    return response()->json($stats);
  }

  /**
   * Get monthly growth data for the last 6 months
   */
  private function getMonthlyGrowthData()
  {
    $data = [];
    for ($i = 5; $i >= 0; $i--) {
      $month = now()->subMonths($i);
      $data[] = [
        'month' => $month->format('M Y'),
        'new_clients' => User::where('role', 'client')
          ->whereMonth('created_at', $month->month)
          ->whereYear('created_at', $month->year)
          ->count(),
      ];
    }
    return $data;
  }

  /**
   * Get recent client activities
   */
  private function getRecentActivities()
  {
    $activities = [];

    // Recent registrations
    $recentClients = User::where('role', 'client')
      ->where('created_at', '>=', now()->subDays(7))
      ->latest()
      ->take(5)
      ->get();

    foreach ($recentClients as $client) {
      $activities[] = [
        'type' => 'registration',
        'description' => 'عميل جديد: ' . $client->name,
        'date' => $client->created_at,
      ];
    }

    return collect($activities)->sortByDesc('date')->values()->all();
  }

  /**
   * Renew client subscription
   */
  public function renewSubscription(User $client)
  {
    if (!$client->isClient() || !$client->clientProfile) {
      return response()->json(['success' => false, 'message' => 'العميل غير موجود أو لا يملك اشتراك']);
    }

    try {
      // Extend subscription by billing cycle period
      $profile = $client->clientProfile;

      if ($profile->subscription_end_date) {
        $newEndDate = $profile->subscription_end_date;
      } else {
        $newEndDate = now();
      }

      // Add time based on billing cycle
      switch ($profile->billing_cycle) {
        case 'monthly':
          $newEndDate = $newEndDate->addMonth();
          break;
        case 'quarterly':
          $newEndDate = $newEndDate->addMonths(3);
          break;
        case 'yearly':
          $newEndDate = $newEndDate->addYear();
          break;
        default:
          $newEndDate = $newEndDate->addMonth();
      }

      $profile->update([
        'subscription_end_date' => $newEndDate,
        'subscription_status' => 'active',
        'payment_status' => 'pending' // Will need payment
      ]);

      return response()->json([
        'success' => true,
        'message' => 'تم تجديد الاشتراك بنجاح',
        'new_end_date' => $newEndDate->format('Y-m-d')
      ]);
    } catch (\Exception $e) {
      return response()->json(['success' => false, 'message' => 'حدث خطأ في تجديد الاشتراك']);
    }
  }

  /**
   * Add device to client
   */
  public function addDevice(Request $request, User $client)
  {
    if (!$client->isClient() || !$client->clientProfile) {
      return response()->json(['success' => false, 'message' => 'العميل غير موجود أو لا يملك اشتراك']);
    }

    $validated = $request->validate([
      'device_name' => 'required|string|max:255',
      'device_type' => 'nullable|in:mobile,tablet,laptop,desktop,tv'
    ]);

    try {
      $profile = $client->clientProfile;
      $currentDevicesCount = $client->clientDevices()->count();

      // Check if client can add more devices
      if ($currentDevicesCount >= $profile->device_limit || $profile->subscription_status !== 'active') {
        return response()->json([
          'success' => false,
          'message' => 'تم الوصول للحد الأقصى من الأجهزة أو الاشتراك غير نشط'
        ]);
      }

      // Create actual device record
      $device = \App\Models\ClientDevice::create([
        'user_id' => $client->id,
        'device_name' => $validated['device_name'],
        'device_type' => $validated['device_type'] ?? 'mobile',
        'device_token' => \App\Models\ClientDevice::generateDeviceToken(),
        'status' => 'active',
        'last_connection' => now(),
        'ip_address' => $request->ip(),
        'device_info' => [
          'user_agent' => $request->userAgent(),
          'app_version' => '2.1.0'
        ]
      ]);

      // Update devices count
      $newCount = $client->clientDevices()->count();
      $profile->update(['devices_count' => $newCount]);

      return response()->json([
        'success' => true,
        'message' => 'تم إضافة الجهاز بنجاح',
        'devices_count' => $newCount,
        'available_devices' => $profile->device_limit - $newCount,
        'device' => [
          'id' => $device->id,
          'name' => $device->device_name,
          'type' => $device->getDeviceTypeText(),
          'status' => $device->getStatusText(),
          'last_connection' => $device->last_connection?->diffForHumans()
        ]
      ]);
    } catch (\Exception $e) {
      return response()->json(['success' => false, 'message' => 'حدث خطأ في إضافة الجهاز']);
    }
  }

  /**
   * Remove device from client
   */
  public function removeDevice(Request $request, User $client, $deviceId)
  {
    if (!$client->isClient()) {
      return response()->json(['success' => false, 'message' => 'العميل غير موجود']);
    }

    try {
      $device = $client->clientDevices()->findOrFail($deviceId);
      $deviceName = $device->device_name;
      $device->delete();

      // Update devices count
      $newCount = $client->clientDevices()->count();
      if ($client->clientProfile) {
        $client->clientProfile->update(['devices_count' => $newCount]);
      }

      return response()->json([
        'success' => true,
        'message' => 'تم حذف الجهاز "' . $deviceName . '" بنجاح',
        'devices_count' => $newCount,
        'available_devices' => $client->clientProfile ? $client->clientProfile->device_limit - $newCount : 0
      ]);
    } catch (\Exception $e) {
      return response()->json(['success' => false, 'message' => 'حدث خطأ في حذف الجهاز']);
    }
  }

  /**
   * Update device status (toggle between active/inactive)
   */
  public function toggleDeviceStatus(Request $request, User $client, $deviceId)
  {
    if (!$client->isClient()) {
      return response()->json(['success' => false, 'message' => 'العميل غير موجود']);
    }

    $validated = $request->validate([
      'status' => 'required|in:active,inactive'
    ]);

    try {
      $device = $client->clientDevices()->findOrFail($deviceId);
      $device->update(['status' => $validated['status']]);

      return response()->json([
        'success' => true,
        'message' => 'تم تحديث حالة الجهاز بنجاح',
        'device' => [
          'id' => $device->id,
          'name' => $device->device_name,
          'status_text' => $device->getStatusText(),
          'status_color' => $device->getStatusColor()
        ]
      ]);
    } catch (\Exception $e) {
      return response()->json(['success' => false, 'message' => 'حدث خطأ في تحديث حالة الجهاز']);
    }
  }

  /**
   * Get device details for modal
   */
  public function getDeviceDetails(User $client, $deviceId)
  {
    if (!$client->isClient()) {
      return response()->json(['success' => false, 'message' => 'العميل غير موجود']);
    }

    try {
      $device = $client->clientDevices()->findOrFail($deviceId);

      return response()->json([
        'success' => true,
        'device' => [
          'id' => $device->id,
          'device_name' => $device->device_name,
          'device_type_text' => $device->getDeviceTypeText(),
          'device_serial' => $device->device_serial,
          'device_model' => $device->device_model,
          'ios_version' => $device->ios_version,
          'status_text' => $device->getStatusText(),
          'status_color' => $device->getStatusColor(),
          'activation_date' => $device->activation_date?->format('Y-m-d'),
          'last_connection' => $device->last_connection?->diffForHumans(),
          'notes' => $device->notes
        ]
      ]);
    } catch (\Exception $e) {
      return response()->json(['success' => false, 'message' => 'حدث خطأ في جلب بيانات الجهاز']);
    }
  }

  /**
   * Toggle subscription status
   */
  public function toggleSubscription(Request $request, User $client)
  {
    if (!$client->isClient() || !$client->clientProfile) {
      return response()->json(['success' => false, 'message' => 'العميل غير موجود أو لا يملك اشتراك']);
    }

    $validated = $request->validate([
      'subscription_id' => 'required|integer',
      'status' => 'required|in:active,inactive'
    ]);

    try {
      $client->clientProfile->update(['subscription_status' => $validated['status']]);

      return response()->json([
        'success' => true,
        'message' => 'تم تحديث حالة الاشتراك بنجاح'
      ]);
    } catch (\Exception $e) {
      return response()->json(['success' => false, 'message' => 'حدث خطأ في تحديث حالة الاشتراك']);
    }
  }

  /**
   * Update client subscription details
   */
  public function updateSubscription(Request $request, User $client)
  {
    if (!$client->isClient()) {
      return back()->with('error', 'العميل غير موجود');
    }

    $validated = $request->validate([
      'subscription_type' => 'required|in:basic,premium,enterprise',
      'subscription_status' => 'required|in:active,inactive,suspended,expired,trial',
      'subscription_start_date' => 'nullable|date',
      'subscription_end_date' => 'nullable|date|after:subscription_start_date',
      'device_limit' => 'required|integer|min:1|max:50',
      'billing_cycle' => 'required|in:monthly,quarterly,semi_annually,annually',
      'payment_status' => 'required|in:paid,pending,overdue',
      'client_notes' => 'nullable|string|max:1000',
    ]);

    try {
      if ($client->clientProfile) {
        $client->clientProfile->update($validated);
      } else {
        ClientProfile::create(array_merge(['user_id' => $client->id], $validated));
      }

      return back()->with('success', 'تم تحديث الاشتراك بنجاح');
    } catch (\Exception $e) {
      return back()->with('error', 'حدث خطأ في تحديث الاشتراك: ' . $e->getMessage());
    }
  }

  /**
   * Create new subscription for client
   */
  public function createSubscription(Request $request, User $client)
  {
    if (!$client->isClient()) {
      return back()->with('error', 'العميل غير موجود');
    }

    if ($client->clientProfile) {
      return back()->with('error', 'العميل يملك اشتراك بالفعل');
    }

    $validated = $request->validate([
      'subscription_type' => 'required|in:basic,premium,enterprise',
      'device_limit' => 'required|integer|min:1|max:50',
      'billing_cycle' => 'required|in:monthly,quarterly,semi_annually,annually',
    ]);

    try {
      ClientProfile::create([
        'user_id' => $client->id,
        'subscription_type' => $validated['subscription_type'],
        'subscription_status' => 'active',
        'device_limit' => $validated['device_limit'],
        'billing_cycle' => $validated['billing_cycle'],
        'subscription_start_date' => now(),
        'subscription_end_date' => now()->addYear(),
        'payment_status' => 'paid',
        'devices_count' => 0,
      ]);

      return back()->with('success', 'تم إنشاء الاشتراك بنجاح');
    } catch (\Exception $e) {
      return back()->with('error', 'حدث خطأ في إنشاء الاشتراك: ' . $e->getMessage());
    }
  }

  /**
   * Delete device (alternative route)
   */
  public function deleteDevice(User $client, $deviceId)
  {
    if (!$client->isClient()) {
      return response()->json(['success' => false, 'message' => 'العميل غير موجود']);
    }

    try {
      $device = $client->clientDevices()->findOrFail($deviceId);
      $deviceName = $device->device_name;
      $device->delete();

      // Update devices count
      $newCount = $client->clientDevices()->count();
      if ($client->clientProfile) {
        $client->clientProfile->update(['devices_count' => $newCount]);
      }

      return response()->json([
        'success' => true,
        'message' => 'تم حذف الجهاز "' . $deviceName . '" بنجاح'
      ]);
    } catch (\Exception $e) {
      return response()->json(['success' => false, 'message' => 'حدث خطأ في حذف الجهاز']);
    }
  }
}
