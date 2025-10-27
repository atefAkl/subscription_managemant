<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>إرسال عرض سعر للطلب #{{ $subscriptionRequest->id }} - لوحة تحكم الإدارة</title>
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font -->
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700&display=swap" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Tajawal', sans-serif;
        }
    </style>
</head>
<body class="bg-gray-100 min-h-screen">
    <!-- Navigation -->
    <nav class="bg-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <div class="flex-shrink-0 flex items-center">
                        <h1 class="text-xl font-bold text-blue-600">إرسال عرض سعر</h1>
                    </div>
                </div>
                
                <div class="flex items-center space-x-4 space-x-reverse">
                    <div class="flex space-x-2 space-x-reverse">
                        <a href="{{ route('admin.subscription-requests.index') }}" class="text-gray-600 hover:text-blue-600 px-3 py-2 rounded-md text-sm">
                            طلبات الاشتراك
                        </a>
                        <a href="{{ route('admin.dashboard') }}" class="text-gray-600 hover:text-blue-600 px-3 py-2 rounded-md text-sm">
                            لوحة التحكم
                        </a>
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="text-gray-600 hover:text-red-600 px-3 py-2 rounded-md text-sm">
                                تسجيل الخروج
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="max-w-4xl mx-auto py-6 sm:px-6 lg:px-8">
        @if($errors->any())
            <div class="bg-red-50 border border-red-300 text-red-700 px-4 py-3 rounded mb-6">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Request Summary -->
        <div class="bg-white overflow-hidden shadow rounded-lg mb-6">
            <div class="px-4 py-5 sm:p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-4">ملخص الطلب</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">معلومات العميل</h3>
                        <div class="space-y-1 text-sm text-gray-600">
                            <p><span class="font-medium">الاسم:</span> {{ $subscriptionRequest->user->name }}</p>
                            <p><span class="font-medium">البريد:</span> {{ $subscriptionRequest->user->email }}</p>
                            <p><span class="font-medium">تاريخ التسجيل:</span> {{ $subscriptionRequest->user->created_at->format('Y-m-d') }}</p>
                        </div>
                    </div>
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">تفاصيل الطلب</h3>
                        <div class="space-y-1 text-sm text-gray-600">
                            <p><span class="font-medium">اسم الاشتراك:</span> {{ $subscriptionRequest->subscription_name }}</p>
                            <p><span class="font-medium">عدد الأجهزة:</span> {{ $subscriptionRequest->device_count }} جهاز</p>
                            <p><span class="font-medium">تاريخ البداية المقترح:</span> {{ $subscriptionRequest->proposed_start_date->format('Y-m-d') }}</p>
                            <p><span class="font-medium">تاريخ الطلب:</span> {{ $subscriptionRequest->created_at->format('Y-m-d H:i') }}</p>
                        </div>
                    </div>
                </div>
                
                @if($subscriptionRequest->notes)
                <div class="mt-4 p-4 bg-gray-50 rounded-lg">
                    <h4 class="font-medium text-gray-900 mb-2">ملاحظات العميل:</h4>
                    <p class="text-sm text-gray-700">{{ $subscriptionRequest->notes }}</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Quote Form -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-6">إرسال عرض السعر</h2>

                <form method="POST" action="{{ route('admin.subscription-requests.quote.send', $subscriptionRequest->id) }}" class="space-y-6">
                    @csrf

                    <!-- Quoted Price -->
                    <div>
                        <label for="quoted_price" class="block text-sm font-medium text-gray-700 mb-2">
                            السعر المقترح (شهرياً)
                        </label>
                        <div class="relative">
                            <input type="number" 
                                   id="quoted_price" 
                                   name="quoted_price" 
                                   step="0.01"
                                   min="0"
                                   class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('quoted_price') border-red-300 @enderror"
                                   placeholder="0.00"
                                   value="{{ old('quoted_price') }}"
                                   required>
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
                        <select id="payment_method" 
                                name="payment_method" 
                                class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('payment_method') border-red-300 @enderror"
                                required>
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
                        <textarea id="admin_notes" 
                                  name="admin_notes" 
                                  rows="4" 
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
                        <a href="{{ route('admin.subscription-requests.show', $subscriptionRequest->id) }}" 
                           class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-3 px-6 rounded">
                            العودة
                        </a>
                        
                        <button type="submit" 
                                class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-8 rounded focus:outline-none focus:shadow-outline">
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
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
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
</body>
</html>