@extends('layouts.app')

@section('content')
<x-breadcrumb :items="[
    [ 'label' => 'الرئيسية', 'url' => route('admin.dashboard') ], 
    [ 'label'=> 'طلبات الاشتراك', 'url' => route('admin.subscription-requests.index') ],
    [ 'label' => 'تفاصيل طلب الاشتراك', 'url' => '' ],
    ]" />
<!-- Main Content -->

<div class="max-w-6xl mx-auto py-2 sm:px-6 lg:px-8">
    @if(session('success'))
    <div class="bg-green-50 border border-green-300 text-green-700 px-4 py-3 rounded mb-6">
        {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div class="bg-red-50 border border-red-300 text-red-700 px-4 py-3 rounded mb-6">
        {{ session('error') }}
    </div>
    @endif

    <!-- Status and Actions Header -->
    <div class="bg-white overflow-hidden shadow rounded-lg mb-6">
        <div class="px-4 py-2 sm:p-3">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">{{ $subscriptionRequest->subscription_name }}</h2>
                    <p class="mt-1 text-sm text-gray-500">
                        طلب رقم #{{ $subscriptionRequest->id }} - تم الإرسال في {{ $subscriptionRequest->created_at->format('Y-m-d H:i') }}
                    </p>
                </div>
                <div class="flex items-center space-x-3 space-x-reverse">
                    <span class="inline-flex items-center px-3 py-2 rounded-full text-sm font-medium 
                            @if($subscriptionRequest->status == 'pending') bg-yellow-100 text-yellow-800
                            @elseif($subscriptionRequest->status == 'quoted') bg-blue-100 text-blue-800
                            @elseif($subscriptionRequest->status == 'paid') bg-green-100 text-green-800
                            @elseif($subscriptionRequest->status == 'active') bg-green-100 text-green-800
                            @elseif($subscriptionRequest->status == 'rejected') bg-red-100 text-red-800
                            @endif">
                        {{ $subscriptionRequest->status_label }}
                    </span>

                    @if($subscriptionRequest->status == 'pending' && $subscriptionRequest->is_demo)
                    <div class="flex space-x-2 space-x-reverse">
                        <a href="{{ route('admin.subscription-requests.quote', $subscriptionRequest->id) }}"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded text-sm font-medium">
                            إرسال عرض سعر
                        </a>

                        <button onclick="showRejectModal()" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded text-sm font-medium">
                            رفض الطلب
                        </button>
                    </div>
                    @endif

                    @if($subscriptionRequest->status == 'paid' && $subscriptionRequest->payments->where('status', 'pending_verification')->count() > 0)
                    <a href="{{ route('admin.payments.pending') }}" class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded text-sm font-medium">
                        التحقق من الدفع
                    </a>
                    @endif

                    @if($subscriptionRequest->status == 'paid' && $subscriptionRequest->subscription)
                    <form action="{{ route('admin.subscriptions.activate', $subscriptionRequest->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded text-sm font-medium">
                            تفعيل الاشتراك
                        </button>
                    </form>
                    @endif

                    @if($subscriptionRequest->status == 'active')
                    <form action="{{ route('admin.subscriptions.deactivate', $subscriptionRequest->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded text-sm font-medium">
                            تعطيل الاشتراك
                        </button>
                    </form>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- Client Information -->
        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 py-2 sm:px-3">
                <h3 class="text-lg leading-6 font-medium text-gray-900">
                    معلومات العميل
                </h3>
            </div>
            <div class="border-t border-gray-200">
                <dl>
                    <div class="bg-gray-50 px-4 py-2 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">الاسم</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $subscriptionRequest->user->name }}</dd>
                    </div>
                    <div class="bg-white px-4 py-2 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">البريد الإلكتروني</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $subscriptionRequest->user->email }}</dd>
                    </div>
                    <div class="bg-gray-50 px-4 py-2 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">تاريخ التسجيل</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $subscriptionRequest->user->created_at->format('Y-m-d') }}</dd>
                    </div>
                    <div class="bg-white px-4 py-2 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">إجمالي الاشتراكات</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $subscriptionRequest->user->subscriptions()->count() }}</dd>
                    </div>
                </dl>
            </div>
        </div>

        <!-- Request Details -->
        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 py-2 sm:px-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900">
                    تفاصيل الطلب
                </h3>
            </div>
            <div class="border-t border-gray-200">
                <dl>
                    <div class="bg-gray-50 px-4 py-2 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">اسم الاشتراك</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $subscriptionRequest->subscription_name }}</dd>
                    </div>
                    <div class="bg-white px-4 py-2 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">عدد الأجهزة</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $subscriptionRequest->device_count }} جهاز</dd>
                    </div>
                    <div class="bg-gray-50 px-4 py-2 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">تاريخ البداية المقترح</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $subscriptionRequest->proposed_start_date->format('Y-m-d') }}</dd>
                    </div>
                    <div class="bg-white px-4 py-2 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">تاريخ الطلب</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $subscriptionRequest->created_at->format('Y-m-d H:i') }}</dd>
                    </div>
                </dl>
            </div>
        </div>
    </div>

    @if($subscriptionRequest->notes)
    <!-- Client Notes -->
    <div class="bg-white shadow overflow-hidden sm:rounded-lg mb-6">
        <div class="px-4 py-2 sm:px-3">
            <h3 class="text-lg leading-6 font-medium text-gray-900">
                ملاحظات العميل
            </h3>
        </div>
        <div class="border-t border-gray-200 px-4 py-2 sm:p-3">
            <p class="text-sm text-gray-900">{{ $subscriptionRequest->notes }}</p>
        </div>
    </div>
    @endif

    @if($subscriptionRequest->status != 'pending')
    <!-- Quote Information -->
    <div class="bg-white shadow overflow-hidden sm:rounded-lg mb-6">
        <div class="px-4 py-2 sm:px-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900">
                عرض السعر
            </h3>
            @if($subscriptionRequest->quoted_at)
            <p class="mt-1 max-w-2xl text-sm text-gray-500">
                تم إرسال العرض في {{ $subscriptionRequest->quoted_at->format('Y-m-d H:i') }}
            </p>
            @endif
        </div>
        <div class="border-t border-gray-200">
            <dl>
                @if($subscriptionRequest->quoted_price)
                <div class="bg-gray-50 px-4 py-2 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">السعر المقترح</dt>
                    <dd class="mt-1 text-lg font-semibold text-green-600 sm:mt-0 sm:col-span-2">
                        {{ number_format($subscriptionRequest->quoted_price, 2) }} جنيه مصري/شهر
                    </dd>
                </div>
                @endif
                @if($subscriptionRequest->payment_method)
                <div class="bg-white px-4 py-2 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">طريقة الدفع</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $subscriptionRequest->getPaymentMethodName() }}</dd>
                </div>
                @endif
                @if($subscriptionRequest->payment_verification_status)
                <div class="bg-gray-50 px-4 py-2 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">حالة التحقق من الدفع</dt>
                    <dd class="mt-1 text-sm sm:mt-0 sm:col-span-2">
                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                            @if($subscriptionRequest->payment_verification_status == 'pending') bg-yellow-100 text-yellow-800
                            @elseif($subscriptionRequest->payment_verification_status == 'verified') bg-green-100 text-green-800
                            @elseif($subscriptionRequest->payment_verification_status == 'rejected') bg-red-100 text-red-800
                            @endif">
                            @if($subscriptionRequest->payment_verification_status == 'pending') في انتظار التحقق
                            @elseif($subscriptionRequest->payment_verification_status == 'verified') تم التحقق من الدفع
                            @elseif($subscriptionRequest->payment_verification_status == 'rejected') مرفوض
                            @endif
                        </span>
                        @if($subscriptionRequest->payment_verified_by)
                        <br><small class="text-gray-500">بواسطة: {{ $subscriptionRequest->paymentVerifiedBy->name ?? 'غير محدد' }}</small>
                        @endif
                    </dd>
                </div>
                @endif
                @if($subscriptionRequest->payment_receipt)
                <div class="bg-white px-4 py-2 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">إيصال الدفع</dt>
                    <dd class="mt-1 text-sm sm:mt-0 sm:col-span-2">
                        <div class="flex items-center space-x-3 space-x-reverse">
                            <a href="{{ asset('storage/' . $subscriptionRequest->payment_receipt) }}" target="_blank"
                                class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                <i class="fas fa-eye ml-1"></i>
                                عرض الإيصال
                            </a>
                            <a href="{{ asset('storage/' . $subscriptionRequest->payment_receipt) }}" download
                                class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                <i class="fas fa-download ml-1"></i>
                                تحميل
                            </a>
                        </div>
                    </dd>
                </div>
                @endif
                @if($subscriptionRequest->admin_notes)
                <div class="bg-gray-50 px-4 py-2 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">ملاحظات الإدارة</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $subscriptionRequest->admin_notes }}</dd>
                </div>
                @endif
            </dl>
        </div>
    </div>
    @endif

    @if($subscriptionRequest->payments->count() > 0)
    <!-- Payment Information -->
    <div class="bg-white shadow overflow-hidden sm:rounded-lg mb-6">
        <div class="px-4 py-2 sm:px-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900">
                معلومات الدفع
            </h3>
        </div>
        <div class="border-t border-gray-200">
            @foreach($subscriptionRequest->payments as $payment)
            <div class="border-b border-gray-100 last:border-b-0">
                <dl>
                    <div class="bg-gray-50 px-4 py-2 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">حالة الدفع</dt>
                        <dd class="mt-1 text-sm sm:mt-0 sm:col-span-2">
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                    @if($payment->status == 'pending_verification') bg-yellow-100 text-yellow-800
                                    @elseif($payment->status == 'verified') bg-green-100 text-green-800
                                    @elseif($payment->status == 'rejected') bg-red-100 text-red-800
                                    @endif">
                                @if($payment->status == 'pending_verification') قيد التحقق
                                @elseif($payment->status == 'verified') تم التحقق
                                @elseif($payment->status == 'rejected') مرفوض
                                @endif
                            </span>
                        </dd>
                    </div>
                    <div class="bg-white px-4 py-2 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">المبلغ</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ number_format($payment->amount, 2) }} جنيه</dd>
                    </div>
                    @if($payment->transaction_reference)
                    <div class="bg-gray-50 px-4 py-2 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">رقم المرجع</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $payment->transaction_reference }}</dd>
                    </div>
                    @endif
                    <div class="bg-white px-4 py-2 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">تاريخ الدفع</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $payment->paid_at->format('Y-m-d H:i') }}</dd>
                    </div>
                    @if($payment->transaction_reference)
                    <div class="bg-gray-50 px-4 py-2 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">إيصال الدفع</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            <a href="{{ asset('storage/payment-receipts/' . $payment->transaction_reference) }}" target="_blank"
                                class="text-blue-600 hover:text-blue-800 underline">
                                عرض الإيصال
                            </a>
                        </dd>
                    </div>
                    @endif
                    @if($payment->notes)
                    <div class="bg-white px-4 py-2 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">ملاحظات الدفع</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $payment->notes }}</dd>
                    </div>
                    @endif
                </dl>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    @if($subscriptionRequest->subscription)
    <!-- Active Subscription -->
    <div class="bg-white shadow overflow-hidden sm:rounded-lg mb-6">
        <div class="px-4 py-2 sm:px-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900">
                الاشتراك النشط
            </h3>
        </div>
        <div class="border-t border-gray-200">
            <dl>
                <div class="bg-gray-50 px-4 py-2 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">رقم الاشتراك</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">#{{ $subscriptionRequest->subscription->id }}</dd>
                </div>
                <div class="bg-white px-4 py-2 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">تاريخ التفعيل</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $subscriptionRequest->subscription->start_date->format('Y-m-d') }}</dd>
                </div>
                <div class="bg-gray-50 px-4 py-2 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">الأجهزة المسجلة</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        {{ $subscriptionRequest->subscription->devices->count() }}/{{ $subscriptionRequest->subscription->device_count }}
                    </dd>
                </div>
            </dl>
        </div>
    </div>
    @endif

    <!-- إدارة حالة الطلب -->
    @if($subscriptionRequest->payment_method && $subscriptionRequest->payment_verification_status == 'pending')
    <div class="bg-white shadow overflow-hidden sm:rounded-lg mb-6">
        <div class="px-4 py-2 sm:px-6 border-b border-gray-200">
            <h3 class="text-lg leading-6 font-medium text-gray-900 flex items-center">
                <i class="fas fa-credit-card text-blue-500 ml-2"></i>
                إدارة الدفع
            </h3>
            <p class="mt-1 max-w-2xl text-sm text-gray-500">
                قم بمراجعة إيصال الدفع واتخاذ الإجراء المناسب
            </p>
        </div>
        <div class="px-4 py-2 sm:px-6">
            <div class="flex space-x-4 space-x-reverse">

                <button onclick="verifyPayment()"
                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700">
                    <i class="fas fa-check ml-1"></i>
                    قبول الدفع
                </button>
                <button onclick="showRejectPaymentModal()"
                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-red-600 hover:bg-red-700">
                    <i class="fas fa-times ml-1"></i>
                    رفض الدفع
                </button>
            </div>
        </div>
    </div>
    @endif

    <!-- قسم الدردشة والتعليقات -->
    <div class="bg-white shadow overflow-hidden sm:rounded-lg mb-6">
        <div class="px-4 py-2 sm:px-6 border-b border-gray-200">
            <h3 class="text-lg leading-6 font-medium text-gray-900 flex items-center">
                <i class="fas fa-comments text-blue-500 ml-2"></i>
                الرسائل والتحديثات
            </h3>
            <p class="mt-1 max-w-2xl text-sm text-gray-500">
                تواصل مع العميل حول طلب الاشتراك
            </p>
        </div>

        <!-- منطقة عرض الرسائل -->
        <div id="chat-messages" class="px-4 py-4 max-h-96 overflow-y-auto space-y-4">
            <!-- سيتم تحميل الرسائل هنا بـ JavaScript -->
        </div>

        <!-- نموذج إرسال رسالة جديدة -->
        <div class="px-4 py-4 border-t border-gray-200 bg-gray-50">
            <form id="send-message-form" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="subscription_request_id" value="{{ $subscriptionRequest->id }}">

                <div class="flex space-x-3 space-x-reverse">
                    <div class="flex-1 form-floating">
                        <textarea name="message" id="message-input" rows="2"
                            class="form-control w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" placeholder="اكتب رسالتك هنا..."
                            required></textarea>

                        <label for="message-attachment" class="">
                            <i class="fas fa-paperclip ml-1"></i>
                            مرفق
                        </label>
                        <input type="file" name="attachments[]" id="message-attachment" class="hidden" accept=".jpg,.jpeg,.png,.pdf" multiple>

                    </div>
                    <button type="submit"
                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <i class="fas fa-paper-plane ml-1"></i>
                        إرسال
                    </button>
                </div>

                <!-- عرض الملفات المختارة -->
                <div id="selected-files" class="mt-2 hidden">
                    <div class="text-sm text-gray-600">الملفات المختارة:</div>
                    <div id="files-list" class="mt-1 space-y-1"></div>
                </div>
            </form>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="flex justify-between">
        <a href="{{ route('admin.subscription-requests.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
            العودة للقائمة
        </a>
    </div>
</div>

<!-- Reject Modal -->
<div id="rejectModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3 text-center">
            <h3 class="text-lg font-medium text-gray-900">رفض الطلب</h3>
            <form method="POST" action="{{ route('admin.subscription-requests.reject', $subscriptionRequest->id) }}" class="mt-4">
                @csrf
                <div class="mb-4">
                    <label for="admin_notes" class="block text-sm font-medium text-gray-700 mb-2">
                        سبب الرفض (مطلوب)
                    </label>
                    <textarea id="admin_notes" name="admin_notes" rows="4" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500"
                        placeholder="يرجى توضيح سبب رفض الطلب..." required></textarea>
                </div>
                <div class="flex justify-between">
                    <button type="button" onclick="hideRejectModal()" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                        إلغاء
                    </button>
                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                        رفض الطلب
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Reject Payment Modal -->
<div id="rejectPaymentModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3 text-center">
            <h3 class="text-lg font-medium text-gray-900">رفض الدفع</h3>
            <form id="reject-payment-form" class="mt-4">
                @csrf
                <div class="mb-4">
                    <label for="payment_rejection_notes" class="block text-sm font-medium text-gray-700 mb-2">
                        سبب رفض الدفع (مطلوب)
                    </label>
                    <textarea id="payment_rejection_notes" name="notes" rows="4" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500"
                        placeholder="يرجى توضيح سبب رفض الدفع..." required></textarea>
                </div>
                <div class="flex justify-between">
                    <button type="button" onclick="hideRejectPaymentModal()" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                        إلغاء
                    </button>
                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                        رفض الدفع
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
    const subscriptionRequestId = {{ $subscriptionRequest->id }};
    const chatMessages = document.getElementById('chat-messages');
    const sendMessageForm = document.getElementById('send-message-form');
    const messageInput = document.getElementById('message-input');
    const attachmentInput = document.getElementById('message-attachment');
    const selectedFiles = document.getElementById('selected-files');
    const filesList = document.getElementById('files-list');

    // تحميل الرسائل
    function loadMessages() {
        console.log('Loading messages for subscription request:', subscriptionRequestId);
        fetch(`/api/subscription-comments?subscription_request_id=${subscriptionRequestId}`)
            .then(response => response.json())
            .then(data => {
                console.log('Messages loaded:', data);
                if (data.success) {
                    displayMessages(data.comments);
                } else {
                    console.error('Failed to load messages:', data);
                }
            })
            .catch(error => console.error('Error loading messages:', error));
    }

    // عرض الرسائل
    function displayMessages(comments) {
        chatMessages.innerHTML = '';
        
        if (comments.length === 0) {
            chatMessages.innerHTML = `
                <div class="text-center text-gray-500 py-8">
                    <i class="fas fa-comments text-4xl mb-2"></i>
                    <p>لا توجد رسائل بعد. ابدأ المحادثة!</p>
                </div>
            `;
            return;
        }

        comments.forEach(comment => {
            const messageDiv = document.createElement('div');
            messageDiv.className = `flex ${comment.is_admin ? 'justify-start' : 'justify-end'}`;
            
            messageDiv.innerHTML = `
                <div class="max-w-xs lg:max-w-md px-4 py-2 rounded-lg ${comment.is_admin ? 'bg-blue-500 text-white' : 'bg-gray-100 text-gray-800'}">
                    <div class="text-xs ${comment.is_admin ? 'text-blue-100' : 'text-gray-600'} mb-1">
                        ${comment.sender_name} - ${comment.created_at_human}
                    </div>
                    <div class="text-sm">${comment.message}</div>
                    ${comment.attachments && comment.attachments.length > 0 ? `
                        <div class="mt-2 space-y-1">
                            ${comment.attachments.map(attachment => `
                                <a href="/storage/${attachment.path}" target="_blank" class="block text-xs underline">
                                    <i class="fas fa-paperclip ml-1"></i>${attachment.name}
                                </a>
                            `).join('')}
                        </div>
                    ` : ''}
                </div>
            `;
            
            chatMessages.appendChild(messageDiv);
        });

        // التمرير لأسفل
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }

    // إرسال رسالة
    if (sendMessageForm) {
        sendMessageForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const submitButton = this.querySelector('button[type="submit"]');
            
            submitButton.disabled = true;
            submitButton.innerHTML = '<i class="fas fa-spinner fa-spin ml-1"></i> جاري الإرسال...';
            
            // إضافة CSRF token إلى FormData
            formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
            
            fetch('/api/subscription-comments', {
                method: 'POST',
                body: formData
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    messageInput.value = '';
                    attachmentInput.value = '';
                    selectedFiles.classList.add('hidden');
                    filesList.innerHTML = '';
                    loadMessages();
                } else {
                    console.error('Server error:', data);
                    alert('حدث خطأ أثناء إرسال الرسالة: ' + (data.message || 'خطأ غير معروف'));
                }
            })
            .catch(error => {
                console.error('Error sending message:', error);
                alert('حدث خطأ أثناء إرسال الرسالة');
            })
            .finally(() => {
                submitButton.disabled = false;
                submitButton.innerHTML = '<i class="fas fa-paper-plane ml-1"></i> إرسال';
            });
        });
    }

    // معالجة اختيار الملفات
    if (attachmentInput) {
        attachmentInput.addEventListener('change', function() {
            const files = Array.from(this.files);
            
            if (files.length > 0) {
                selectedFiles.classList.remove('hidden');
                filesList.innerHTML = files.map(file => `
                    <div class="text-sm text-gray-600">
                        <i class="fas fa-file ml-1"></i>${file.name} (${(file.size / 1024).toFixed(1)} KB)
                    </div>
                `).join('');
            } else {
                selectedFiles.classList.add('hidden');
            }
        });
    }

    // تحميل الرسائل عند بداية الصفحة
    loadMessages();

    // تحديث الرسائل كل 30 ثانية
    setInterval(loadMessages, 30000);
});

// دوال إدارة الدفع
function verifyPayment() {
    if (confirm('هل أنت متأكد من قبول هذا الدفع؟')) {
        fetch(`/api/subscription-requests/{{ $subscriptionRequest->id }}/verify-payment`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                action: 'verify'
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('حدث خطأ: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('حدث خطأ أثناء معالجة الطلب');
        });
    }
}

function showRejectPaymentModal() {
    document.getElementById('rejectPaymentModal').classList.remove('hidden');
}

function hideRejectPaymentModal() {
    document.getElementById('rejectPaymentModal').classList.add('hidden');
}

// معالجة رفض الدفع
document.getElementById('reject-payment-form').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const notes = formData.get('notes');
    
    fetch(`/api/subscription-requests/{{ $subscriptionRequest->id }}/verify-payment`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            action: 'reject',
            notes: notes
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('حدث خطأ: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('حدث خطأ أثناء معالجة الطلب');
    });
});

// دوال أخرى
function showRejectModal() {
    document.getElementById('rejectModal').classList.remove('hidden');
}

function hideRejectModal() {
    document.getElementById('rejectModal').classList.add('hidden');
}
</script>
@endsection