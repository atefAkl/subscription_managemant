<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SubscriptionRequest;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubscriptionRequestController extends Controller
{
    /**
     * عرض قائمة طلبات الاشتراك
     */
    public function index(Request $request)
    {
        $status = $request->get('status', 'all');

        $query = SubscriptionRequest::with(['user', 'payments'])
            ->orderBy('created_at', 'desc');

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        $subscriptionRequests = $query->paginate(10);

        // إحصائيات الطلبات
        $stats = [
            'pending' => SubscriptionRequest::where('status', 'pending')->count(),
            'quoted' => SubscriptionRequest::where('status', 'quoted')->count(),
            'paid' => SubscriptionRequest::where('status', 'paid')->count(),
            'active' => SubscriptionRequest::where('status', 'active')->count(),
            'rejected' => SubscriptionRequest::where('status', 'rejected')->count(),
        ];

        $totalRequests = SubscriptionRequest::count();

        return view('admin.subscription-requests.index', compact('subscriptionRequests', 'status', 'stats', 'totalRequests'));
    }

    /**
     * عرض تفاصيل طلب اشتراك
     */
    public function show($id)
    {
        $subscriptionRequest = SubscriptionRequest::with(['user', 'payments', 'subscription.devices'])
            ->findOrFail($id);

        return view('admin.subscription-requests.show', compact('subscriptionRequest'));
    }

    /**
     * عرض نموذج إرسال عرض السعر
     */
    public function showQuoteForm($id)
    {
        $subscriptionRequest = SubscriptionRequest::where('status', 'pending')
            ->findOrFail($id);

        return view('admin.subscription-requests.quote', compact('subscriptionRequest'));
    }

    /**
     * إرسال عرض السعر
     */
    public function sendQuote(Request $request, $id)
    {
        $subscriptionRequest = SubscriptionRequest::where('status', 'pending')
            ->findOrFail($id);

        $validated = $request->validate([
            'quoted_price' => 'required|numeric|min:0',
            'payment_method' => 'required|string|max:500',
            'admin_notes' => 'nullable|string|max:1000'
        ], [
            'quoted_price.required' => 'السعر المقترح مطلوب',
            'quoted_price.numeric' => 'السعر يجب أن يكون رقماً',
            'payment_method.required' => 'طريقة الدفع مطلوبة'
        ]);

        $subscriptionRequest->update([
            'quoted_price' => $validated['quoted_price'],
            'payment_method' => $validated['payment_method'],
            'admin_notes' => $validated['admin_notes'],
            'quoted_at' => now(),
            'status' => 'quoted'
        ]);

        return redirect()->route('admin.subscription-requests.index')
            ->with('success', 'تم إرسال عرض السعر بنجاح للعميل.');
    }

    /**
     * رفض طلب الاشتراك
     */
    public function reject(Request $request, $id)
    {
        $subscriptionRequest = SubscriptionRequest::where('status', 'pending')
            ->findOrFail($id);

        $validated = $request->validate([
            'admin_notes' => 'required|string|max:1000'
        ], [
            'admin_notes.required' => 'سبب الرفض مطلوب'
        ]);

        $subscriptionRequest->update([
            'admin_notes' => $validated['admin_notes'],
            'status' => 'rejected'
        ]);

        return redirect()->route('admin.subscription-requests.index')
            ->with('success', 'تم رفض الطلب وإرسال إشعار للعميل.');
    }

    /**
     * عرض المدفوعات المعلقة للتحقق
     */
    public function pendingPayments()
    {
        $payments = Payment::with(['subscriptionRequest.user', 'user'])
            ->where('status', 'pending_verification')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.payments.pending', compact('payments'));
    }

    /**
     * التحقق من الدفعة
     */
    public function verifyPayment(Request $request, $paymentId)
    {
        try {
            $payment = Payment::where('status', 'pending_verification')->findOrFail($paymentId);

            $validated = $request->validate([
                'admin_notes' => 'nullable|string|max:1000'
            ]);

            $payment->verify(Auth::id(), $validated['admin_notes']);

            // تحديث حالة طلب الاشتراك
            if ($payment->subscriptionRequest) {
                $payment->subscriptionRequest->update(['status' => 'active']);
            }

            return response()->json([
                'success' => true,
                'message' => 'تم التحقق من الدفعة بنجاح وتفعيل الاشتراك.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * رفض الدفعة
     */
    public function rejectPayment(Request $request, $paymentId)
    {
        try {
            $payment = Payment::where('status', 'pending_verification')->findOrFail($paymentId);

            $validated = $request->validate([
                'reason' => 'nullable|string|max:1000'
            ]);

            $payment->reject(Auth::id(), $validated['reason']);

            return response()->json([
                'success' => true,
                'message' => 'تم رفض الدفعة وإرسال إشعار للعميل.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * عرض تفاصيل الدفعة
     */
    public function paymentDetails($paymentId)
    {
        $payment = Payment::with(['user', 'subscriptionRequest'])
            ->findOrFail($paymentId);

        return response()->json([
            'success' => true,
            'payment' => [
                'id' => $payment->id,
                'amount' => number_format($payment->amount, 2),
                'payment_method' => $payment->payment_method ?? 'غير محدد',
                'transaction_id' => $payment->transaction_reference ?? '',
                'notes' => $payment->admin_notes ?? '',
                'receipt_path' => $payment->receipt_path ?? null,
                'status' => $payment->status,
                'status_label' => $payment->status_label,
                'created_at' => $payment->created_at->format('Y-m-d H:i'),
                'paid_at' => $payment->paid_at ? $payment->paid_at->format('Y-m-d H:i') : null,
                'verified_at' => $payment->verified_at ? $payment->verified_at->format('Y-m-d H:i') : null,
                'user' => $payment->user ? [
                    'name' => $payment->user->name,
                    'email' => $payment->user->email,
                    'id' => $payment->user->id
                ] : null,
                'subscription_request' => $payment->subscriptionRequest ? [
                    'id' => $payment->subscriptionRequest->id,
                    'subscription_name' => $payment->subscriptionRequest->subscription_name,
                    'device_count' => $payment->subscriptionRequest->device_count,
                    'quoted_price' => $payment->subscriptionRequest->quoted_price
                ] : null
            ]
        ]);
    }
}
