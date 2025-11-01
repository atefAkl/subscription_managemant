@extends('layouts.app')
@section('title')إرسال عرض سعر للطلب لوحة تحكم الإدارة @endsection
@section('content')

<x-breadcrumb :items="[
        ['label' => 'طلبات الاشتراك', 'url' => route('admin.subscription-requests.index')], 
        ['label' => ' عرض سعر', 'url' => route('admin.subscription-requests.quote', $subscriptionRequest->id)]
    ]" />
<div class="bg-white overflow-hidden shadow rounded-lg">
    <div class="px-4 py-5 mt-12 sm:p-6">
        <!-- Quote Form -->
        <h2 class="text-xl font-bold text-gray-900 mb-6"> عرض السعر</h2>

        <form method="POST" action="{{ route('admin.subscription-requests.quote.send', $subscriptionRequest->id) }}" class="space-y-6">
            @csrf

            <!-- Quoted Price -->
            <div>
                <label for="quoted_price" class="block text-sm font-medium text-gray-700 mb-2">
                    السعر المقترح (شهرياً)
                </label>
                <div class="relative">
                    <input type="number" id="quoted_price" name="quoted_price" step="0.01" min="0"
                        class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('quoted_price') border-red-300 @enderror"
                        placeholder="0.00" value="{{ old('quoted_price') }}" required>
                    <div class="absolute inset-y-0 left-0 pr-3 flex items-center pointer-events-none">
                        <span class="text-gray-500 text-sm">ج.م</span>
                    </div>
                </div>
                @error('quoted_price')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                <p class="mt-1 text-sm text-gray-500">
                    أدخل السعر الشهري للاشتراك بالجنيه المصري
                </p>
            </div>

            <!-- Payment Method -->
            <div>
                <label for="payment_method" class="block text-sm font-medium text-gray-700 mb-2">
                    طريقة الدفع المقترحة
                </label>
                <select id="payment_method" name="payment_method"
                    class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('payment_method') border-red-300 @enderror" required>
                    <option value="">اختر طريقة الدفع</option>
                    <option value="bank_transfer" {{ old('payment_method') == 'bank_transfer' ? 'selected' : '' }}>تحويل بنكي</option>
                    <option value="vodafone_cash" {{ old('payment_method') == 'vodafone_cash' ? 'selected' : '' }}>فودافون كاش</option>
                    <option value="orange_cash" {{ old('payment_method') == 'orange_cash' ? 'selected' : '' }}>أورانج كاش</option>
                    <option value="etisalat_cash" {{ old('payment_method') == 'etisalat_cash' ? 'selected' : '' }}>اتصالات كاش</option>
                </select>
                @error('payment_method')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Admin Notes -->
            <div>
                <label for="admin_notes" class="block text-sm font-medium text-gray-700 mb-2">
                    ملاحظات للعميل (اختياري)
                </label>
                <textarea id="admin_notes" name="admin_notes" rows="4"
                    class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('admin_notes') border-red-300 @enderror"
                    placeholder="أي ملاحظات أو تعليمات إضافية للعميل...">{{ old('admin_notes') }}</textarea>
                @error('admin_notes')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                <p class="mt-1 text-sm text-gray-500">
                    يمكنك إضافة أي تفاصيل إضافية حول الخدمة أو شروط خاصة
                </p>
            </div>

            <!-- Pricing Guidelines -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <h4 class="text-sm font-medium text-blue-900 mb-2">إرشادات التسعير</h4>
                <div class="text-sm text-blue-800 space-y-1">
                    <p>• السعر الأساسي لجهاز واحد: 50 ج.م/شهر</p>
                    <p>• لكل جهاز إضافي: +30 ج.م/شهر</p>
                    <p>• خصم للاشتراكات طويلة المدى: 10% للسنة كاملة</p>
                    <p>• خصم العملاء الجدد: 15% للشهر الأول</p>
                </div>
            </div>

            <!-- Calculation Helper -->
            <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                <h4 class="text-sm font-medium text-gray-900 mb-2">حاسبة السعر المقترح</h4>
                <div class="text-sm text-gray-700 space-y-1">
                    <p>عدد الأجهزة المطلوبة: <span class="font-medium">{{ $subscriptionRequest->device_count }}</span></p>
                    <p>السعر الأساسي المقترح: <span class="font-medium" id="suggested-price">{{ 50 + (($subscriptionRequest->device_count - 1) * 30) }} ج.م/شهر</span></p>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-between pt-6">
                <a href="{{ route('admin.subscription-requests.show', $subscriptionRequest->id) }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-3 px-6 rounded">
                    العودة
                </a>

                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-8 rounded focus:outline-none focus:shadow-outline">
                    إرسال عرض السعر
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Warning -->
<div class="mt-6 bg-yellow-50 border border-yellow-200 rounded-lg p-4">
    <div class="flex">
        <div class="flex-shrink-0">
            <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd"
                    d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                    clip-rule="evenodd"></path>
            </svg>
        </div>
        <div class="mr-3">
            <h3 class="text-sm font-medium text-yellow-800">تنبيه هام</h3>
            <div class="mt-1 text-sm text-yellow-700">
                <ul class="list-disc list-inside space-y-1">
                    <li>سيتم إرسال عرض السعر للعميل فوراً ولا يمكن التراجع</li>
                    <li>تأكد من صحة السعر وطريقة الدفع قبل الإرسال</li>
                    <li>العميل سيتمكن من الدفع مباشرة بعد استلام العرض</li>
                </ul>
            </div>
        </div>
    </div>
</div>


<script>
    // Auto-fill suggested price
        document.addEventListener('DOMContentLoaded', function() {
            const suggestedPrice = {{ 50 + (($subscriptionRequest->device_count - 1) * 30) }};
            const priceInput = document.getElementById('quoted_price');
            
            if (!priceInput.value) {
                priceInput.value = suggestedPrice;
            }
        });
</script>
@endsection