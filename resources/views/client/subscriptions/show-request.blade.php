@extends('layouts.client')
@section('title', 'تفاصيل طلب الاشتراك')
@section('content')
<!-- Main Content -->
<div class="max-w-4xl mx-auto py-6 sm:px-6 lg:px-8">
    <!-- Breadcrumb & Back Button -->
    <x-breadcrumb :items="[
            ['label' => 'الاشتراكات', 'url' => route('client.subscriptions')],
            ['label' => 'تفاصيل الطلب #' . $subscriptionRequest->serial_number, 'url' => '']
        ]" />

    <x-back-button :url="route('client.subscriptions')" label="العودة للاشتراكات" />

    <!-- Status Header -->
    <div class="bg-white overflow-hidden shadow rounded-lg mb-6">
        <div class="px-4 py-5 sm:p-6">
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
    <div class="bg-white shadow overflow-hidden sm:rounded-lg mb-6">
        <div class="px-4 py-5 sm:px-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900">
                تفاصيل الطلب
            </h3>
        </div>
        <div class="border-t border-gray-200">
            <dl>
                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">
                        اسم الاشتراك
                    </dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        {{ $subscriptionRequest->subscription_name }}
                    </dd>
                </div>
                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">
                        عدد الأجهزة المطلوبة
                    </dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        {{ $subscriptionRequest->device_count }} {{ $subscriptionRequest->device_count == 1 ? 'جهاز' : 'أجهزة' }}
                    </dd>
                </div>
                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">
                        تاريخ البداية المقترح
                    </dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        {{ $subscriptionRequest->proposed_start_date->format('Y-m-d') }}
                    </dd>
                </div>
                @if($subscriptionRequest->notes)
                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
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
    <div class="bg-white shadow overflow-hidden sm:rounded-lg mb-6">
        <div class="px-4 py-5 sm:px-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900">
                عرض السعر
            </h3>
            <p class="mt-1 max-w-2xl text-sm text-gray-500">
                تم إرسال عرض السعر في {{ $subscriptionRequest->quoted_at->format('Y-m-d H:i') }}
            </p>
        </div>
        <div class="border-t border-gray-200">
            <dl>
                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">
                        السعر الشهري
                    </dt>
                    <dd class="mt-1 text-lg font-semibold text-green-600 sm:mt-0 sm:col-span-2">
                        {{ number_format($subscriptionRequest->quoted_price, 2) }} جنيه مصري
                    </dd>
                </div>
                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">
                        طريقة الدفع المقترحة
                    </dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        {{ $subscriptionRequest->payment_method }}
                    </dd>
                </div>
                @if($subscriptionRequest->admin_notes)
                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
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
    <div class="bg-white shadow overflow-hidden sm:rounded-lg mb-6">
        <div class="px-4 py-5 sm:px-6">
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
                <div class="px-4 py-4 sm:px-6">
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
    <div class="bg-white shadow overflow-hidden sm:rounded-lg mb-6">
        <div class="px-4 py-5 sm:px-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900">
                معلومات الدفع
            </h3>
        </div>
        <div class="border-t border-gray-200">
            <dl>
                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">
                        تاريخ الدفع
                    </dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        {{ $subscriptionRequest->paid_at ? $subscriptionRequest->paid_at->format('Y-m-d H:i') : 'غير محدد' }}
                    </dd>
                </div>
                @if($subscriptionRequest->payment_receipt)
                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
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

    <!-- Action Buttons -->
    <div class="flex justify-between">
        <a href="{{ route('client.subscriptions') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
            العودة للاشتراكات
        </a>

        @if($subscriptionRequest->status == 'quoted')
        <a href="{{ route('client.subscription-requests.payment', $subscriptionRequest->id) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
            متابعة الدفع
        </a>
        @endif
    </div>
</div>
@endsection