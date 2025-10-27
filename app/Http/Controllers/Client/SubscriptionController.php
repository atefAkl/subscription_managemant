<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\SubscriptionRequest;
use App\Models\Subscription;
use App\Models\Device;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SubscriptionController extends Controller
{
    /**
     * عرض صفحة الاشتراكات الرئيسية
     */
    public function index()
    {
        $user = Auth::user();

        // جلب الاشتراكات النشطة
        $activeSubscriptions = Subscription::where('user_id', $user->id)
            ->where('status', 'active')
            ->with(['devices' => function ($query) {
                $query->select('id', 'subscription_id', 'device_identifier', 'iphone_model', 'device_nickname', 'status', 'last_connected_at');
            }])
            ->get();

        // جلب طلبات الاشتراك الحالية
        $subscriptionRequests = SubscriptionRequest::where('user_id', $user->id)
            ->whereIn('status', ['pending', 'quoted', 'paid'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('client.subscriptions.index', compact('activeSubscriptions', 'subscriptionRequests'));
    }

    /**
     * عرض نموذج طلب اشتراك جديد
     */
    public function create()
    {
        return view('client.subscriptions.create');
    }

    /**
     * حفظ طلب اشتراك جديد
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'subscription_name' => 'required|string|max:255',
            'device_count' => 'required|integer|min:1|max:10',
            'proposed_start_date' => 'required|date|after_or_equal:today',
            'notes' => 'nullable|string|max:1000'
        ], [
            'subscription_name.required' => 'اسم الاشتراك مطلوب',
            'device_count.required' => 'عدد الأجهزة مطلوب',
            'device_count.min' => 'يجب أن يكون عدد الأجهزة جهاز واحد على الأقل',
            'device_count.max' => 'عدد الأجهزة لا يمكن أن يتجاوز 10 أجهزة',
            'proposed_start_date.required' => 'تاريخ البداية مطلوب',
            'proposed_start_date.after_or_equal' => 'تاريخ البداية لا يمكن أن يكون في الماضي'
        ]);

        $validated['user_id'] = Auth::id();

        SubscriptionRequest::create($validated);

        return redirect()->route('client.subscriptions')
            ->with('success', 'تم إرسال طلب الاشتراك بنجاح! سيتم مراجعته وإرسال عرض السعر قريباً.');
    }

    /**
     * عرض تفاصيل طلب اشتراك
     */
    public function showRequest($id)
    {
        $subscriptionRequest = SubscriptionRequest::where('user_id', Auth::id())
            ->with('requestDevices')
            ->findOrFail($id);

        return view('client.subscriptions.show-request', compact('subscriptionRequest'));
    }

    /**
     * عرض نموذج السداد
     */
    public function showPayment($id)
    {
        $subscriptionRequest = SubscriptionRequest::where('user_id', Auth::id())
            ->where('status', 'quoted')
            ->findOrFail($id);

        return view('client.subscriptions.payment', compact('subscriptionRequest'));
    }

    /**
     * معالجة السداد
     */
    public function processPayment(Request $request, $id)
    {
        $subscriptionRequest = SubscriptionRequest::where('user_id', Auth::id())
            ->where('status', 'quoted')
            ->findOrFail($id);

        $validated = $request->validate([
            'transaction_reference' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'payment_receipt' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'payment_notes' => 'nullable|string|max:1000',
            'terms_agreement' => 'required|accepted'
        ], [
            'transaction_reference.required' => 'رقم المرجع أو العملية مطلوب',
            'amount.required' => 'المبلغ المدفوع مطلوب',
            'payment_receipt.required' => 'إيصال الدفع مطلوب',
            'payment_receipt.file' => 'يجب رفع ملف صحيح',
            'payment_receipt.mimes' => 'نوع الملف يجب أن يكون: jpg, jpeg, png, pdf',
            'payment_receipt.max' => 'حجم الملف يجب ألا يتجاوز 2 ميجابايت',
            'terms_agreement.required' => 'يجب الموافقة على شروط وأحكام الخدمة'
        ]);

        // التحقق من مطابقة المبلغ
        if ($validated['amount'] != $subscriptionRequest->quoted_price) {
            return back()->withErrors(['amount' => 'المبلغ المدفوع يجب أن يطابق السعر المقترح: ' . number_format($subscriptionRequest->quoted_price, 2) . ' جنيه']);
        }

        // رفع الإيصال
        $receiptPath = $request->file('payment_receipt')->store('payment-receipts', 'public');

        // إنشاء سجل الدفع
        $payment = $subscriptionRequest->payments()->create([
            'user_id' => Auth::id(),
            'amount' => $validated['amount'],
            'payment_method' => $subscriptionRequest->payment_method,
            'transaction_reference' => $validated['transaction_reference'],
            'receipt_path' => $receiptPath,
            'admin_notes' => $validated['payment_notes'] ?? null,
            'status' => 'pending_verification',
            'paid_at' => now()
        ]);

        // تحديث طلب الاشتراك
        $subscriptionRequest->update([
            'status' => 'paid',
            'paid_at' => now(),
            'payment_receipt' => $receiptPath
        ]);

        return redirect()->route('client.subscription-requests.show', $subscriptionRequest->id)
            ->with('success', 'تم إرسال بيانات الدفع بنجاح! سيتم مراجعتها خلال 24 ساعة.');
    }

    /**
     * عرض صفحة تخصيص الاشتراك
     */
    public function showCustomization($id)
    {
        $subscriptionRequest = SubscriptionRequest::where('user_id', Auth::id())
            ->with('requestDevices')
            ->findOrFail($id);

        // تحديد ما إذا كان يمكن التعديل أم لا
        $canEdit = in_array($subscriptionRequest->status, ['pending', 'quoted']);

        return view('client.subscriptions.customize-request', compact('subscriptionRequest', 'canEdit'));
    }

    /**
     * تحديث تخصيص الاشتراك
     */
    public function updateCustomization(Request $request, $id)
    {
        $subscriptionRequest = SubscriptionRequest::where('user_id', Auth::id())
            ->findOrFail($id);

        // التحقق من أن الطلب قابل للتعديل
        if (!in_array($subscriptionRequest->status, ['pending', 'quoted'])) {
            return redirect()->route('client.subscription-requests.show', $id)
                ->with('error', 'لا يمكن تعديل هذا الاشتراك في الحالة الحالية');
        }

        $validated = $request->validate([
            'subscription_name' => 'required|string|max:255',
            'device_count' => 'required|integer|min:1|max:20',
            'proposed_start_date' => 'required|date|after_or_equal:today',
            'notes' => 'nullable|string|max:1000'
        ], [
            'subscription_name.required' => 'اسم الاشتراك مطلوب',
            'device_count.required' => 'عدد الأجهزة مطلوب',
            'device_count.min' => 'يجب أن يكون عدد الأجهزة جهاز واحد على الأقل',
            'device_count.max' => 'لا يمكن أن يتجاوز عدد الأجهزة 20 جهاز',
            'proposed_start_date.required' => 'تاريخ البداية المقترح مطلوب',
            'proposed_start_date.after_or_equal' => 'تاريخ البداية يجب أن يكون اليوم أو تاريخ مستقبلي'
        ]);

        $subscriptionRequest->update($validated);

        return redirect()->route('client.subscription-requests.customize', $id)
            ->with('success', 'تم تحديث تفاصيل الاشتراك بنجاح');
    }

    /**
     * إضافة جهاز لطلب الاشتراك
     */
    public function addDeviceToRequest(Request $request, $id)
    {
        $subscriptionRequest = SubscriptionRequest::where('user_id', Auth::id())
            ->with('requestDevices')
            ->findOrFail($id);

        // التحقق من أن الطلب قابل للتعديل
        if (!in_array($subscriptionRequest->status, ['pending', 'quoted'])) {
            return response()->json(['success' => false, 'message' => 'لا يمكن إضافة أجهزة لهذا الطلب في الحالة الحالية'], 400);
        }

        // التحقق من عدم تجاوز العدد المطلوب
        if ($subscriptionRequest->requestDevices->count() >= $subscriptionRequest->device_count) {
            return response()->json(['success' => false, 'message' => 'تم الوصول للعدد الأقصى من الأجهزة المطلوبة'], 400);
        }

        $validated = $request->validate([
            'device_identifier' => 'required|string|size:10|regex:/^[a-zA-Z0-9]+$/|unique:subscription_request_devices,device_identifier',
            'iphone_model' => 'required|string',
            'device_nickname' => 'required|string|max:100',
            'special_requirements' => 'nullable|string|max:500'
        ], [
            'device_identifier.required' => 'رقم الجهاز المميز مطلوب',
            'device_identifier.size' => 'رقم الجهاز المميز يجب أن يكون 10 خانات بالضبط',
            'device_identifier.regex' => 'رقم الجهاز المميز يجب أن يحتوي على أحرف وأرقام فقط',
            'device_identifier.unique' => 'رقم الجهاز المميز مستخدم بالفعل',
            'iphone_model.required' => 'طراز الآيفون مطلوب',
            'device_nickname.required' => 'اسم الجهاز مطلوب',
            'device_nickname.max' => 'اسم الجهاز يجب ألا يتجاوز 100 حرف'
        ]);

        $device = $subscriptionRequest->requestDevices()->create($validated);

        return response()->json([
            'success' => true,
            'message' => 'تم إضافة الجهاز بنجاح',
            'device' => [
                'id' => $device->id,
                'device_identifier' => $device->device_identifier,
                'iphone_model' => $device->iphone_model,
                'device_nickname' => $device->device_nickname,
                'display_name' => $device->display_name
            ]
        ]);
    }

    /**
     * عرض تفاصيل اشتراك نشط
     */
    public function show($id)
    {
        $subscription = Subscription::where('user_id', Auth::id())
            ->with(['devices', 'subscriptionRequest'])
            ->findOrFail($id);

        return view('client.subscriptions.show', compact('subscription'));
    }

    /**
     * عرض صفحة إدارة الأجهزة لاشتراك معين
     */
    public function manageDevices($id)
    {
        $subscription = Subscription::where('user_id', Auth::id())
            ->with('devices')
            ->findOrFail($id);

        return view('client.subscriptions.manage-devices', compact('subscription'));
    }

    /**
     * إضافة جهاز آيفون جديد (في حالة وجود مساحة)
     */
    public function addDevice(Request $request, $id)
    {
        $subscription = Subscription::where('user_id', Auth::id())->findOrFail($id);

        if ($subscription->device_count <= $subscription->devices()->count()) {
            return back()->with('error', 'لا يمكن إضافة أجهزة أكثر. تم الوصول للحد الأقصى.');
        }

        $validated = $request->validate([
            'device_identifier' => 'required|string|size:10|alpha_num|unique:devices,device_identifier',
            'iphone_model' => 'required|string|max:255',
            'device_nickname' => 'nullable|string|max:255',
        ], [
            'device_identifier.required' => 'رقم الجهاز المميز مطلوب',
            'device_identifier.size' => 'رقم الجهاز يجب أن يكون 10 خانات بالضبط',
            'device_identifier.alpha_num' => 'رقم الجهاز يجب أن يحتوي على أرقام وحروف فقط',
            'device_identifier.unique' => 'رقم الجهاز هذا مستخدم بالفعل',
            'iphone_model.required' => 'طراز الآيفون مطلوب',
            'device_nickname.max' => 'الاسم المميز لا يمكن أن يتجاوز 255 حرف',
        ]);

        $device = $subscription->devices()->create([
            'device_number' => 'iPhone-' . $validated['device_identifier'],
            'device_identifier' => strtoupper($validated['device_identifier']),
            'iphone_model' => $validated['iphone_model'],
            'device_nickname' => $validated['device_nickname'],
            'status' => 'pending'
        ]);

        return back()->with('success', 'تم إضافة جهاز الآيفون بنجاح. سيتم تفعيله من قبل المدير قريباً.');
    }
}
