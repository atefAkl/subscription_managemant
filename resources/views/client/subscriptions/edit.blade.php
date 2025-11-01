@extends('layouts.client')

@section('title', 'طلب اشتراك جديد')

@section('content')
<!-- Main Content -->
<div class="max-w-3xl mx-auto py-6 sm:px-6 lg:px-8 mt-12">
    <x-breadcrumb :items="[
    ['label' => 'لوحة الادارة', 'url' => route('client.dashboard')], 
    ['label' => 'ادارة الاشتراكات', 'url' => route('client.subscriptions')], 
    ['label' => 'تحديث بيانات الطلب', 'url' => '#']
]" />
    <!-- Header -->
    <div class="mb-6">
        <h3 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl">
            تحديث بيانات الطلب #{{$subscription->serial_number}}
        </h3>
        <p class="mt-1 text-sm text-gray-500">
            يمكنك تعديل الطلب مالم يكن نشطا.
        </p>
    </div>

    <!-- Form -->
    <div class="bg-white shadow sm:rounded-lg">
        <form method="POST" action="{{ route('client.subscriptions.update', $subscription->id) }}">
            @csrf
            @method('PUT')
            <div class="px-4 py-5 sm:p-6">
                <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-2">
                    <!-- اسم الاشتراك -->
                    <div class="sm:col-span-2">
                        <label for="subscription_name" class="block text-sm font-medium text-gray-700">
                            اسم الاشتراك *
                        </label>
                        <div class="mt-1">
                            <input type="text" name="subscription_name" id="subscription_name" value="{{ old('subscription_name') }}"
                                class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md"
                                placeholder="مثال: اشتراك المكتب الرئيسي">
                        </div>
                        <p class="mt-2 text-sm text-gray-500">
                            اختر اسماً مميزاً يساعدك على التفريق بين اشتراكاتك المختلفة
                        </p>
                        @error('subscription_name')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- عدد الأجهزة -->
                    <div>
                        <label for="device_count" class="block text-sm font-medium text-gray-700">
                            عدد الأجهزة المطلوبة *
                        </label>
                        <div class="mt-1">
                            <select name="device_count" id="device_count"
                                class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                @for($i = 1; $i <= 10; $i++) <option value="{{ $i }}" {{ old('device_count') == $i ? 'selected' : '' }}>
                                    {{ $i }} {{ $i == 1 ? 'جهاز' : 'أجهزة' }}
                                    </option>
                                    @endfor
                            </select>
                        </div>
                        @error('device_count')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- تاريخ البداية المقترح -->
                    <div>
                        <label for="proposed_start_date" class="block text-sm font-medium text-gray-700">
                            تاريخ البداية المقترح *
                        </label>
                        <div class="mt-1">
                            <input type="date" name="proposed_start_date" id="proposed_start_date" value="{{ old('proposed_start_date', date('Y-m-d')) }}" min="{{ date('Y-m-d') }}"
                                class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">
                        </div>
                        @error('proposed_start_date')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- ملاحظات إضافية -->
                    <div class="sm:col-span-2">
                        <label for="notes" class="block text-sm font-medium text-gray-700">
                            ملاحظات إضافية
                        </label>
                        <div class="mt-1">
                            <textarea name="notes" id="notes" rows="4"
                                class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md"
                                placeholder="أي ملاحظات أو متطلبات خاصة...">{{ old('notes') }}</textarea>
                        </div>
                        <p class="mt-2 text-sm text-gray-500">
                            اكتب أي متطلبات خاصة أو ملاحظات تريد إيصالها للفريق الإداري
                        </p>
                        @error('notes')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- معلومات إضافية -->
                <div class="mt-6 bg-blue-50 rounded-lg p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                    clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <div class="mr-3">
                            <h3 class="text-sm font-medium text-blue-800">
                                ماذا يحدث بعد إرسال الطلب؟
                            </h3>
                            <div class="mt-2 text-sm text-blue-700">
                                <ul class="list-disc list-inside space-y-1">
                                    <li>سيتم مراجعة طلبك من قبل الفريق الإداري</li>
                                    <li>ستتلقى عرض سعر مفصل خلال 24-48 ساعة</li>
                                    <li>بعد الموافقة والسداد، سيتم تفعيل الخدمة</li>
                                    <li>ستتمكن من إدارة أجهزتك من لوحة التحكم</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="px-4 py-3 bg-gray-50 text-right sm:px-6 sm:rounded-b-lg">
                <div class="flex justify-between">
                    <a href="{{ route('client.subscriptions') }}"
                        class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        إلغاء
                    </a>
                    <button type="submit"
                        class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        إرسال الطلب
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Example Cards -->
    <div class="mt-8">
        <h3 class="text-lg font-medium text-gray-900 mb-4">أمثلة على الاشتراكات</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-4">
                    <h4 class="text-sm font-semibold text-gray-900">مثال 1: مكتب صغير</h4>
                    <p class="text-sm text-gray-600 mt-1">3 أجهزة كمبيوتر + 2 هاتف</p>
                    <p class="text-xs text-gray-500 mt-2">مناسب للمكاتب الصغيرة والشركات الناشئة</p>
                </div>
            </div>
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-4">
                    <h4 class="text-sm font-semibold text-gray-900">مثال 2: مكتب متوسط</h4>
                    <p class="text-sm text-gray-600 mt-1">7 أجهزة كمبيوتر + خادم واحد</p>
                    <p class="text-xs text-gray-500 mt-2">مناسب للشركات المتوسطة والفروع</p>
                </div>
            </div>
        </div>
    </div>
</div>
</body>

</html>