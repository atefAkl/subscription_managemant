<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>دفع الاشتراك - نظام إدارة الاشتراكات</title>
    
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
                        <h1 class="text-xl font-bold text-blue-600">دفع الاشتراك</h1>
                    </div>
                </div>
                
                <div class="flex items-center space-x-4 space-x-reverse">
                    <div class="flex space-x-2 space-x-reverse">
                        <a href="{{ route('client.subscriptions') }}" class="text-gray-600 hover:text-blue-600 px-3 py-2 rounded-md text-sm">
                            الاشتراكات
                        </a>
                        <a href="{{ route('client.dashboard') }}" class="text-gray-600 hover:text-blue-600 px-3 py-2 rounded-md text-sm">
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
        <!-- Subscription Summary -->
        <div class="bg-white overflow-hidden shadow rounded-lg mb-6">
            <div class="px-4 py-5 sm:p-6">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">ملخص الاشتراك</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">{{ $subscriptionRequest->subscription_name }}</h3>
                        <div class="space-y-2 text-sm text-gray-600">
                            <p><span class="font-medium">عدد الأجهزة:</span> {{ $subscriptionRequest->device_count }}</p>
                            <p><span class="font-medium">تاريخ البداية:</span> {{ $subscriptionRequest->proposed_start_date->format('Y-m-d') }}</p>
                            <p><span class="font-medium">طريقة الدفع:</span> {{ $subscriptionRequest->payment_method }}</p>
                        </div>
                    </div>
                    <div class="bg-green-50 p-4 rounded-lg">
                        <div class="text-center">
                            <p class="text-sm text-gray-600 mb-1">المبلغ المطلوب</p>
                            <p class="text-3xl font-bold text-green-600">{{ number_format($subscriptionRequest->quoted_price, 2) }}</p>
                            <p class="text-sm text-gray-600">جنيه مصري شهرياً</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Payment Form -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-6">تأكيد الدفع</h2>

                @if($errors->any())
                    <div class="bg-red-50 border border-red-300 text-red-700 px-4 py-3 rounded mb-6">
                        <ul class="list-disc list-inside">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if(session('success'))
                    <div class="bg-green-50 border border-green-300 text-green-700 px-4 py-3 rounded mb-6">
                        {{ session('success') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('client.subscription-requests.process-payment', $subscriptionRequest->id) }}" enctype="multipart/form-data" class="space-y-6">
                    @csrf

                    <!-- Payment Method Instructions -->
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <h3 class="text-lg font-medium text-blue-900 mb-2">تعليمات الدفع - {{ $subscriptionRequest->payment_method }}</h3>
                        
                        @if($subscriptionRequest->payment_method == 'bank_transfer')
                            <div class="text-sm text-blue-800 space-y-2">
                                <p><strong>بيانات الحساب البنكي:</strong></p>
                                <p>البنك: البنك الأهلي المصري</p>
                                <p>رقم الحساب: 123456789012</p>
                                <p>اسم المستفيد: شركة إدارة الاشتراكات المحدودة</p>
                                <p class="mt-2 font-medium">يرجى تحويل المبلغ ورفع صورة من إيصال التحويل أدناه</p>
                            </div>
                        @elseif($subscriptionRequest->payment_method == 'vodafone_cash')
                            <div class="text-sm text-blue-800 space-y-2">
                                <p><strong>فودافون كاش:</strong></p>
                                <p>الرقم: 01012345678</p>
                                <p>اسم المحفظة: شركة إدارة الاشتراكات</p>
                                <p class="mt-2 font-medium">يرجى إرسال المبلغ ورفع صورة من رسالة التأكيد أدناه</p>
                            </div>
                        @elseif($subscriptionRequest->payment_method == 'orange_cash')
                            <div class="text-sm text-blue-800 space-y-2">
                                <p><strong>أورانج كاش:</strong></p>
                                <p>الرقم: 01087654321</p>
                                <p>اسم المحفظة: شركة إدارة الاشتراكات</p>
                                <p class="mt-2 font-medium">يرجى إرسال المبلغ ورفع صورة من رسالة التأكيد أدناه</p>
                            </div>
                        @elseif($subscriptionRequest->payment_method == 'etisalat_cash')
                            <div class="text-sm text-blue-800 space-y-2">
                                <p><strong>اتصالات كاش:</strong></p>
                                <p>الرقم: 01198765432</p>
                                <p>اسم المحفظة: شركة إدارة الاشتراكات</p>
                                <p class="mt-2 font-medium">يرجى إرسال المبلغ ورفع صورة من رسالة التأكيد أدناه</p>
                            </div>
                        @endif
                    </div>

                    <!-- Transaction Reference -->
                    <div>
                        <label for="transaction_reference" class="block text-sm font-medium text-gray-700 mb-2">
                            رقم العملية أو المرجع
                        </label>
                        <input type="text" 
                               id="transaction_reference" 
                               name="transaction_reference" 
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                               placeholder="أدخل رقم العملية أو المرجع"
                               required>
                        <p class="mt-1 text-sm text-gray-500">
                            رقم العملية الموجود في إيصال الدفع أو رسالة التأكيد
                        </p>
                    </div>

                    <!-- Payment Amount -->
                    <div>
                        <label for="amount" class="block text-sm font-medium text-gray-700 mb-2">
                            المبلغ المدفوع
                        </label>
                        <input type="number" 
                               id="amount" 
                               name="amount" 
                               step="0.01"
                               value="{{ $subscriptionRequest->quoted_price }}"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                               required>
                        <p class="mt-1 text-sm text-gray-500">
                            يجب أن يطابق المبلغ المطلوب: {{ number_format($subscriptionRequest->quoted_price, 2) }} جنيه
                        </p>
                    </div>

                    <!-- Payment Receipt -->
                    <div>
                        <label for="payment_receipt" class="block text-sm font-medium text-gray-700 mb-2">
                            إيصال الدفع
                        </label>
                        <input type="file" 
                               id="payment_receipt" 
                               name="payment_receipt" 
                               accept="image/*,.pdf"
                               class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                               required>
                        <p class="mt-1 text-sm text-gray-500">
                            يرجى رفع صورة واضحة من إيصال الدفع (JPG, PNG, PDF)
                        </p>
                    </div>

                    <!-- Payment Notes -->
                    <div>
                        <label for="payment_notes" class="block text-sm font-medium text-gray-700 mb-2">
                            ملاحظات إضافية (اختياري)
                        </label>
                        <textarea id="payment_notes" 
                                  name="payment_notes" 
                                  rows="3" 
                                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                  placeholder="أي ملاحظات إضافية حول عملية الدفع"></textarea>
                    </div>

                    <!-- Terms Agreement -->
                    <div class="flex items-start">
                        <div class="flex items-center h-5">
                            <input id="terms_agreement" 
                                   name="terms_agreement" 
                                   type="checkbox" 
                                   class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 rounded"
                                   required>
                        </div>
                        <div class="mr-3 text-sm">
                            <label for="terms_agreement" class="font-medium text-gray-700">
                                أوافق على شروط وأحكام الخدمة
                            </label>
                            <p class="text-gray-500">
                                بالمتابعة أؤكد أنني قد دفعت المبلغ المطلوب وأوافق على شروط الخدمة
                            </p>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex justify-between pt-6">
                        <a href="{{ route('client.subscription-requests.show', $subscriptionRequest->id) }}" 
                           class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-3 px-6 rounded">
                            العودة
                        </a>
                        
                        <button type="submit" 
                                class="bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-8 rounded focus:outline-none focus:shadow-outline">
                            تأكيد الدفع
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Security Notice -->
        <div class="mt-6 bg-yellow-50 border border-yellow-200 rounded-lg p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <div class="mr-3">
                    <h3 class="text-sm font-medium text-yellow-800">تنبيه أمني</h3>
                    <div class="mt-1 text-sm text-yellow-700">
                        <ul class="list-disc list-inside space-y-1">
                            <li>تأكد من صحة البيانات المدخلة قبل التأكيد</li>
                            <li>احتفظ بنسخة من إيصال الدفع لمراجعتك الشخصية</li>
                            <li>سيتم مراجعة طلبك خلال 24 ساعة عمل</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>