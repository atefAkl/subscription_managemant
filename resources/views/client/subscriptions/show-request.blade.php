@extends('layouts.client')
@section('title', 'تفاصيل طلب الاشتراك')
@section('content')
<!-- Main Content -->
<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <!-- Breadcrumb & Back Button -->
    <x-breadcrumb :items="[
            ['label' => 'الاشتراكات', 'url' => route('client.subscriptions')],
            ['label' => 'تفاصيل الطلب #' . $subscriptionRequest->serial_number, 'url' => '']
        ]" />

    <x-back-button :url="route('client.subscriptions')" label="العودة للاشتراكات" />

    <!-- Status Header -->
    <div class="bg-white overflow-hidden shadow-sm rounded-lg mb-6">
        <div class="px-4 py-2 sm:p-6">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">{{ $subscriptionRequest->subscription_name }}</h2>
                    <p class="mt-1 text-sm text-gray-500">
                        طلب رقم #{{ $subscriptionRequest->serial_number }} - تم الإرسال في {{ $subscriptionRequest->created_at->format('Y-m-d H:i') }}
                    </p>
                </div>
                <div>
                    <span class="inline-flex items-center px-3 py-2 rounded-full text-sm font-medium 
                            @if($subscriptionRequest->status == 'pending') bg-yellow-100 text-yellow-800
                            @elseif($subscriptionRequest->status == 'quoted') bg-blue-100 text-blue-800
                            @elseif($subscriptionRequest->status == 'paid') bg-green-100 text-green-800
                            @elseif($subscriptionRequest->status == 'active') bg-green-100 text-green-800
                            @elseif($subscriptionRequest->status == 'rejected') bg-red-100 text-red-800
                            @endif">
                        {{ $subscriptionRequest->status_label }}
                    </span>
                </div>
            </div>

            <!-- معلومات الدفع -->
            @if($subscriptionRequest->payment_method)
            <div class="mt-4 bg-gradient-to-r from-green-50 to-emerald-50 border border-green-200 rounded-lg p-4">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="h-10 w-10 bg-green-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-credit-card text-green-600"></i>
                        </div>
                    </div>
                    <div class="mr-3">
                        <h3 class="text-sm font-medium text-green-900">معلومات الدفع</h3>
                        <p class="mt-1 text-sm text-green-700">
                            وسيلة الدفع: {{ $subscriptionRequest->getPaymentMethodName() }}
                            @if($subscriptionRequest->quoted_price)
                            - المبلغ: {{ number_format($subscriptionRequest->quoted_price, 2) }} جنيه
                            @endif
                        </p>
                        @if($subscriptionRequest->payment_verification_status)
                        <p class="mt-1 text-xs">
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                    @if($subscriptionRequest->payment_verification_status == 'pending') bg-yellow-100 text-yellow-800
                                    @elseif($subscriptionRequest->payment_verification_status == 'verified') bg-green-100 text-green-800
                                    @elseif($subscriptionRequest->payment_verification_status == 'rejected') bg-red-100 text-red-800
                                    @endif">
                                @if($subscriptionRequest->payment_verification_status == 'pending') في انتظار التحقق
                                @elseif($subscriptionRequest->payment_verification_status == 'verified') تم التحقق من الدفع
                                @elseif($subscriptionRequest->payment_verification_status == 'rejected') مرفوض
                                @endif
                            </span>
                        </p>
                        @endif
                    </div>
                </div>
            </div>
            @endif

            <!-- زر تخصيص الاشتراك - ثابت في جميع الحالات -->
            <div class="mt-4 bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-lg p-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="h-10 w-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                <span class="text-blue-600 text-xl">📱</span>
                            </div>
                        </div>
                        <div class="mr-3">
                            <h3 class="text-sm font-medium text-blue-900">تخصيص اشتراك iPhone</h3>
                            <p class="mt-1 text-sm text-blue-700">
                                قم بتخصيص تفاصيل الاشتراك وإضافة أجهزة iPhone المطلوبة
                            </p>
                        </div>
                    </div>
                    <div>
                        @if(in_array($subscriptionRequest->status, ['pending', 'quoted']))
                        <a href="{{ route('client.subscription-requests.customize', $subscriptionRequest->id) }}"
                            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 shadow-sm">
                            📱 تخصيص الاشتراك
                        </a>
                        @else
                        <a href="{{ route('client.subscription-requests.customize', $subscriptionRequest->id) }}"
                            class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 shadow-sm">
                            👁️ عرض التخصيص
                        </a>
                        @endif
                    </div>
                </div>
            </div>

            @if($subscriptionRequest->status == 'quoted')
            <div class="mt-4 bg-blue-50 border border-blue-200 rounded-lg p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <div class="mr-3 flex-1">
                        <h3 class="text-sm font-medium text-blue-800">تم إرسال عرض السعر</h3>
                        <p class="mt-1 text-sm text-blue-700">
                            تم مراجعة طلبك وإرسال عرض السعر. يمكنك الآن المتابعة للدفع لتفعيل الخدمة.
                        </p>
                        <div class="mt-3 flex space-x-3 space-x-reverse">
                            <a href="{{ route('client.subscription-requests.payment', $subscriptionRequest->id) }}"
                                class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                                متابعة الدفع
                            </a>
                            <a href="{{ route('client.subscription-requests.customize', $subscriptionRequest->id) }}"
                                class="inline-flex items-center px-3 py-2 border border-blue-300 text-sm leading-4 font-medium rounded-md text-blue-700 bg-blue-100 hover:bg-blue-200">
                                📱 تخصيص الأجهزة
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            @if($subscriptionRequest->status == 'pending')
            <div class="mt-4 bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <div class="mr-3 flex-1">
                        <h3 class="text-sm font-medium text-yellow-800">في انتظار مراجعة الطلب</h3>
                        <p class="mt-1 text-sm text-yellow-700">
                            طلبك قيد المراجعة من قبل فريق الدعم. يمكنك تخصيص تفاصيل الاشتراك وإضافة أجهزة iPhone المطلوبة.
                        </p>
                        <div class="mt-3">
                            <a href="{{ route('client.subscription-requests.customize', $subscriptionRequest->id) }}"
                                class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-yellow-600 hover:bg-yellow-700">
                                📱 تخصيص الاشتراك
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            @if($subscriptionRequest->status == 'rejected')
            <div class="mt-4 bg-red-50 border border-red-200 rounded-lg p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <div class="mr-3">
                        <h3 class="text-sm font-medium text-red-800">تم رفض الطلب</h3>
                        <p class="mt-1 text-sm text-red-700">
                            {{ $subscriptionRequest->admin_notes ?? 'لم يتم توضيح سبب الرفض' }}
                        </p>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- Request Details -->
    <div class="bg-white shadow-sm overflow-hidden sm:rounded-lg mb-6">
        <div class="px-4 py-2 sm:px-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900">
                تفاصيل الطلب
            </h3>
        </div>
        <div class="border-t border-gray-200">
            <dl>
                <div class="bg-gray-50 px-4 py-2 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">
                        اسم الاشتراك
                    </dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        {{ $subscriptionRequest->subscription_name }}
                    </dd>
                </div>
                <div class="bg-white px-4 py-2 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">
                        عدد الأجهزة المطلوبة
                    </dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        {{ $subscriptionRequest->device_count }} {{ $subscriptionRequest->device_count == 1 ? 'جهاز' : 'أجهزة' }}
                    </dd>
                </div>
                <div class="bg-gray-50 px-4 py-2 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">
                        تاريخ البداية المقترح
                    </dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        {{ $subscriptionRequest->proposed_start_date->format('Y-m-d') }}
                    </dd>
                </div>
                @if($subscriptionRequest->notes)
                <div class="bg-white px-4 py-2 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">
                        ملاحظات إضافية
                    </dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        {{ $subscriptionRequest->notes }}
                    </dd>
                </div>
                @endif
            </dl>
        </div>
    </div>

    @if($subscriptionRequest->status != 'pending' && $subscriptionRequest->quoted_price)
    <!-- Quote Details -->
    <div class="bg-white shadow-sm overflow-hidden sm:rounded-lg mb-6">
        <div class="px-4 py-2 sm:px-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900">
                عرض السعر
            </h3>
            <p class="mt-1 max-w-2xl text-sm text-gray-500">
                تم إرسال عرض السعر في {{ $subscriptionRequest->quoted_at }}
            </p>
        </div>
        <div class="border-t border-gray-200">
            <dl>
                <div class="bg-gray-50 px-4 py-2 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">
                        السعر الشهري
                    </dt>
                    <dd class="mt-1 text-lg font-semibold text-green-600 sm:mt-0 sm:col-span-2">
                        {{ number_format($subscriptionRequest->quoted_price, 2) }} جنيه مصري
                    </dd>
                </div>
                <div class="bg-white px-4 py-2 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">
                        طريقة الدفع المقترحة
                    </dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        {{ $subscriptionRequest->payment_method }}
                    </dd>
                </div>
                @if($subscriptionRequest->admin_notes)
                <div class="bg-gray-50 px-4 py-2 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">
                        ملاحظات الإدارة
                    </dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        {{ $subscriptionRequest->admin_notes }}
                    </dd>
                </div>
                @endif
            </dl>
        </div>
    </div>
    @endif

    @if($subscriptionRequest->requestDevices->count() > 0)
    <!-- الأجهزة المخصصة -->
    <div class="bg-white shadow-sm overflow-hidden sm:rounded-lg mb-6">
        <div class="px-4 py-2 sm:px-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900">
                📱 أجهزة iPhone المطلوبة
            </h3>
            <p class="mt-1 max-w-2xl text-sm text-gray-500">
                الأجهزة التي تم تخصيصها لهذا الاشتراك
            </p>
        </div>
        <div class="border-t border-gray-200">
            <div class="divide-y divide-gray-200">
                @foreach($subscriptionRequest->requestDevices as $device)
                <div class="px-4 py-2 sm:px-6">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                                    <span class="text-blue-600 font-semibold text-sm">📱</span>
                                </div>
                            </div>
                            <div class="mr-4">
                                <div class="text-sm font-medium text-gray-900">
                                    {{ $device->device_nickname }}
                                </div>
                                <div class="text-sm text-gray-500">
                                    {{ $device->iphone_model }}
                                </div>
                            </div>
                        </div>
                        <div class="text-left">
                            <div class="text-xs font-mono text-blue-600 bg-blue-50 px-2 py-1 rounded">
                                {{ $device->device_identifier }}
                            </div>
                            @if($device->special_requirements)
                            <div class="text-xs text-gray-500 mt-1">
                                {{ Str::limit($device->special_requirements, 50) }}
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    @if($subscriptionRequest->status == 'paid' || $subscriptionRequest->status == 'active')
    <!-- Payment Information -->
    <div class="bg-white shadow-sm overflow-hidden sm:rounded-lg mb-6">
        <div class="px-4 py-2 sm:px-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900">
                معلومات الدفع
            </h3>
        </div>
        <div class="border-t border-gray-200">
            <dl>
                <div class="bg-gray-50 px-4 py-2 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">
                        تاريخ الدفع
                    </dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        {{ $subscriptionRequest->paid_at ? $subscriptionRequest->paid_at->format('Y-m-d H:i') : 'غير محدد' }}
                    </dd>
                </div>
                @if($subscriptionRequest->payment_receipt)
                <div class="bg-white px-4 py-2 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">
                        إيصال الدفع
                    </dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        <a href="{{ asset('storage/' . $subscriptionRequest->payment_receipt) }}" target="_blank" class="text-blue-600 hover:text-blue-800">
                            عرض الإيصال
                        </a>
                    </dd>
                </div>
                @endif
            </dl>
        </div>
    </div>
    @endif

    <!-- قسم الدردشة والتعليقات -->
    <div class="bg-white shadow-sm overflow-hidden sm:rounded-lg mb-6">
        <div class="px-4 py-2 sm:px-6 border-b border-gray-200">
            <h3 class="text-lg leading-6 font-medium text-gray-900 flex items-center">
                <i class="fas fa-comments text-blue-500 ml-2"></i>
                الرسائل والتحديثات
            </h3>
            <p class="mt-1 max-w-2xl text-sm text-gray-500">
                تواصل مع فريق الدعم حول طلب الاشتراك
            </p>
        </div>

        <!-- منطقة عرض الرسائل -->
        <div id="chat-messages" class="px-4 py-2 max-h-96 overflow-y-auto space-y-4">
            <!-- سيتم تحميل الرسائل هنا بـ JavaScript -->
        </div>

        <!-- نموذج إرسال رسالة جديدة -->
        <div class="px-4 py-2 border-t border-gray-200 bg-gray-50">
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

    @if($subscriptionRequest->status == 'paid' && $subscriptionRequest->subscription)
    <!-- قسم رفع الشهادات -->
    <div class="bg-white shadow-sm overflow-hidden sm:rounded-lg mb-6">
        <div class="px-4 py-2 sm:px-6 border-b border-gray-200">
            <h3 class="text-lg leading-6 font-medium text-gray-900 flex items-center">
                <i class="fas fa-certificate text-green-500 ml-2"></i>
                رفع شهادات Apple Developer
            </h3>
            <p class="mt-1 max-w-2xl text-sm text-gray-500">
                يرجى رفع شهادات Apple Developer الخاصة بك لتفعيل الاشتراك بالكامل
            </p>
        </div>

        <div class="px-4 py-2 sm:px-6">
            <!-- عرض الشهادات الموجودة -->
            <div class="flex items-center justify-between mb-2">
                <h4 class="text-sm font-medium text-gray-700">الشهادات المضافة</h4>
                <button id="copy-certificates-btn"
                    class="inline-flex items-center px-3 py-2 text-white border border-gray-300 shadow-sm text-xs font-medium rounded-md text-gray-700 bg-success hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <i class="fas fa-copy ml-1"></i>
                    نسخ جميع الأكواد
                </button>
            </div>
            <div id="certificates-list" class="mb-2">
                <!-- سيتم تحميل الشهادات هنا بـ JavaScript -->
            </div>
        </div>

        <!-- نموذج إضافة شهادة جديدة -->
        <form id="add-certificate-form" class=" p-4">
            @csrf
            <input type="hidden" name="subscription_id" value="{{ $subscriptionRequest->subscription->id }}">

            <div class="flex space-x-3 space-x-reverse">
                <div class="input-group flex-1">
                    <input type="text" name="certificate_code" id="certificate-code"
                        class="form-control block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                        placeholder="كود الشهادة (10 خانات - أرقام وحروف إنجليزية كبيرة)" maxlength="10" pattern="[A-Z0-9]{10}" required>
                </div>
                <div class="input-group flex-1">
                    <input type="text" name="certificate_description" id="certificate-description"
                        class="form-control block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                        placeholder="وصف الشهادة (مثال: شهادة التطوير - iOS)" required>
                </div>
            </div>
            <p class="text-xs text-gray-500 mt-2">
                اكتب كود الشهادة ووصفها ثم اضغط Enter لإضافتها
            </p>
        </form>
    </div>
    @endif

    <!-- Action Buttons -->
    <div class="flex justify-between">
        <a href="{{ route('client.subscriptions') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
            العودة للاشتراكات
        </a>

        @if($subscriptionRequest->status == 'pending')
        <a href="{{ route('client.subscription-requests.payment', $subscriptionRequest->id) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
            متابعة الدفع
        </a>
        @endif
    </div>
</div>

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
    const subscriptionRequestId = {{ $subscriptionRequest->id }};
    const chatMessages = document.getElementById('chat-messages');
    const sendMessageForm = document.getElementById('send-message-form');
    const messageInput = document.getElementById('message-input');
    const attachmentInput = document.getElementById('message-attachment');
    const selectedFiles = document.getElementById('selected-files');
    const filesList = document.getElementById('files-list');
    const copyCertificatesBtn = document.getElementById('copy-certificates-btn');

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
                <div class="max-w-xs lg:max-w-md px-4 py-2 rounded-lg ${comment.is_admin ? 'bg-gray-100 text-gray-800' : 'bg-blue-500 text-white'}">
                    <div class="text-xs ${comment.is_admin ? 'text-gray-600' : 'text-blue-100'} mb-1">
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

    // معالجة اختيار الملفات
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

    // تحميل الرسائل عند بداية الصفحة
    loadMessages();

    // تحديث الرسائل كل 30 ثانية
    setInterval(loadMessages, 30000);

    // معالجة إضافة الشهادات
    const addCertificateForm = document.getElementById('add-certificate-form');
    const certificateCode = document.getElementById('certificate-code');
    const certificateDescription = document.getElementById('certificate-description');
    const certificatesList = document.getElementById('certificates-list');

    if (addCertificateForm) {
        // إضافة الشهادة عند الضغط على Enter
        addCertificateForm.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                addCertificate();
            }
        });

        function addCertificate() {
            const code = certificateCode.value.trim().toUpperCase();
            const description = certificateDescription.value.trim();

            if (!code) {
                alert('يرجى إدخال كود الشهادة');
                return;
            }

            if (code.length !== 10 || !/^[A-Z0-9]{10}$/.test(code)) {
                alert('كود الشهادة يجب أن يكون 10 خانات من الأرقام والحروف الإنجليزية الكبيرة فقط');
                return;
            }

            const formData = new FormData();
            formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
            formData.append('subscription_id', '{{ $subscriptionRequest->subscription->id }}');
            formData.append('certificate_key', code.toUpperCase());
            formData.append('description', description);

            fetch('/api/subscription-certificates/{{ $subscriptionRequest->subscription->id }}', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    certificateCode.value = '';
                    certificateDescription.value = '';
                    loadCertificates();
                } else {
                    alert('حدث خطأ: ' + (data.message || 'خطأ غير معروف'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('حدث خطأ أثناء إضافة الشهادة');
            });
        }

        function loadCertificates() {
            fetch(`/api/subscription-certificates/{{ $subscriptionRequest->subscription->id }}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        displayCertificates(data.certificates);
                    }
                })
                .catch(error => console.error('Error loading certificates:', error));
        }

        function displayCertificates(certificates) {
            if (!certificates || certificates.length === 0) {
                certificatesList.innerHTML = '<p class="text-gray-500 text-sm">لا توجد شهادات مضافة بعد</p>';
                if (copyCertificatesBtn) {
                    copyCertificatesBtn.disabled = true;
                }
                return;
            }

            if (copyCertificatesBtn) {
                copyCertificatesBtn.disabled = false;
            }

            certificatesList.innerHTML = certificates.map(cert => `
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg mb-2">
                    <div>
                        <div class="flex items-center space-x-2 space-x-reverse">
                            <span class="font-mono text-sm font-bold text-blue-600">${cert.certificate_key}</span>
                            <button type="button" class="text-xs text-blue-600 hover:text-blue-800 underline" onclick="copySingleCertificate('${cert.certificate_key}')">نسخ الكود</button>
                        </div>
                        <div class="text-sm text-gray-600">${cert.description}</div>
                    </div>
                    <div class="text-xs text-gray-500">
                        ${cert.created_at}
                    </div>
                </div>
            `).join('');
        }

        // نسخ كود شهادة واحدة
        function copySingleCertificate(code) {
            const text = code;
            if (navigator.clipboard && navigator.clipboard.writeText) {
                navigator.clipboard.writeText(text)
                    .then(() => alert('تم نسخ كود الشهادة للحافظة'))
                    .catch(err => {
                        console.error('Clipboard API error:', err);
                        alert('فشل في نسخ الكود للحافظة');
                    });
            } else {
                try {
                    const textarea = document.createElement('textarea');
                    textarea.value = text;
                    textarea.style.position = 'fixed';
                    textarea.style.opacity = '0';
                    document.body.appendChild(textarea);
                    textarea.select();
                    const successful = document.execCommand('copy');
                    document.body.removeChild(textarea);
                    if (successful) {
                        alert('تم نسخ كود الشهادة للحافظة');
                    } else {
                        alert('لم يتمكن المتصفح من نسخ الكود تلقائياً، برجاء النسخ يدوياً.');
                    }
                } catch (err) {
                    console.error('execCommand copy error:', err);
                    alert('فشل في نسخ الكود للحافظة');
                }
            }
        }

        // إتاحة الدالة للاستخدام من الـ onclick
        window.copySingleCertificate = copySingleCertificate;

        function copyAllCertificates() {
            if (!copyCertificatesBtn) {
                return;
            }

            fetch(`/api/subscription-certificates/{{ $subscriptionRequest->subscription->id }}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success && data.certificates && data.certificates.length > 0) {
                        const codes = data.certificates.map(cert => cert.certificate_key).join('\n');

                        const originalText = copyCertificatesBtn.innerHTML;
                        const originalClass = copyCertificatesBtn.className;

                        // المتصفحات الحديثة (HTTPS)
                        if (navigator.clipboard && navigator.clipboard.writeText) {
                            navigator.clipboard.writeText(codes)
                                .then(() => {
                                    copyCertificatesBtn.innerHTML = '<i class="fas fa-check ml-1"></i>تم النسخ!';
                                    copyCertificatesBtn.className = originalClass + ' bg-green-50 text-green-700 border-green-300';

                                    setTimeout(() => {
                                        copyCertificatesBtn.innerHTML = originalText;
                                        copyCertificatesBtn.className = originalClass;
                                    }, 2000);
                                })
                                .catch(err => {
                                    console.error('فشل في النسخ (Clipboard API):', err);
                                    alert('فشل في نسخ الأكواد للحافظة');
                                });
                        } else {
                            // Fallback للبيئات التي لا تدعم navigator.clipboard (مثل HTTP أو متصفحات قديمة)
                            try {
                                const textarea = document.createElement('textarea');
                                textarea.value = codes;
                                textarea.style.position = 'fixed';
                                textarea.style.opacity = '0';
                                document.body.appendChild(textarea);
                                textarea.select();

                                const successful = document.execCommand('copy');
                                document.body.removeChild(textarea);

                                if (successful) {
                                    copyCertificatesBtn.innerHTML = '<i class="fas fa-check ml-1"></i>تم النسخ!';
                                    copyCertificatesBtn.className = originalClass + ' bg-green-50 text-green-700 border-green-300';

                                    setTimeout(() => {
                                        copyCertificatesBtn.innerHTML = originalText;
                                        copyCertificatesBtn.className = originalClass;
                                    }, 2000);
                                } else {
                                    alert('لم يتمكن المتصفح من نسخ الأكواد تلقائياً، برجاء النسخ يدوياً.');
                                }
                            } catch (err) {
                                console.error('فشل في النسخ (execCommand):', err);
                                alert('فشل في نسخ الأكواد للحافظة');
                            }
                        }
                    } else {
                        alert('لا توجد شهادات للنسخ');
                    }
                })
                .catch(error => {
                    console.error('خطأ في تحميل الشهادات:', error);
                    alert('حدث خطأ أثناء تحميل الشهادات');
                });
        }

        if (copyCertificatesBtn) {
            copyCertificatesBtn.addEventListener('click', function(e) {
                e.preventDefault();
                copyAllCertificates();
            });
        }

        // تحميل الشهادات عند بداية الصفحة
        loadCertificates();
    }
});
</script>