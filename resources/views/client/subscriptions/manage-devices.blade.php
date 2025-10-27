<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>إدارة الأجهزة - نظام إدارة الاشتراكات</title>
    
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
                        <h1 class="text-xl font-bold text-blue-600">إدارة الأجهزة</h1>
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
    <div class="max-w-6xl mx-auto py-6 sm:px-6 lg:px-8">
        <!-- Subscription Info -->
        <div class="bg-white overflow-hidden shadow rounded-lg mb-6">
            <div class="px-4 py-5 sm:p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-2">{{ $subscription->name }}</h2>
                <div class="flex items-center space-x-4 space-x-reverse text-sm text-gray-600">
                    <span>الأجهزة المستخدمة: {{ $subscription->devices()->count() }}/{{ $subscription->device_count }}</span>
                    <span>•</span>
                    <span>السعر: {{ number_format($subscription->price, 2) }} جنيه/شهر</span>
                    <span>•</span>
                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium 
                        @if($subscription->status == 'active') bg-green-100 text-green-800
                        @elseif($subscription->status == 'expired') bg-red-100 text-red-800
                        @elseif($subscription->status == 'suspended') bg-yellow-100 text-yellow-800
                        @endif">
                        @if($subscription->status == 'active') نشط
                        @elseif($subscription->status == 'expired') منتهي الصلاحية
                        @elseif($subscription->status == 'suspended') معلق
                        @endif
                    </span>
                </div>
            </div>
        </div>

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

        <!-- Add New Device Form -->
        @if($subscription->device_count > $subscription->devices()->count())
        <div class="bg-white shadow overflow-hidden sm:rounded-lg mb-6">
            <div class="px-4 py-5 sm:px-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900">
                    إضافة جهاز آيفون جديد
                </h3>
                <p class="mt-1 max-w-2xl text-sm text-gray-500">
                    يمكنك إضافة {{ $subscription->device_count - $subscription->devices()->count() }} جهاز آيفون إضافي
                </p>
            </div>
            <div class="border-t border-gray-200 px-4 py-5 sm:p-6">
                <form method="POST" action="{{ route('client.subscriptions.devices.add', $subscription->id) }}" class="space-y-6">
                    @csrf
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="device_identifier" class="block text-sm font-medium text-gray-700 mb-2">
                                رقم الجهاز المميز <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   id="device_identifier" 
                                   name="device_identifier" 
                                   maxlength="10"
                                   pattern="[A-Za-z0-9]{10}"
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('device_identifier') border-red-300 @enderror"
                                   placeholder="مثال: ABC1234567"
                                   value="{{ old('device_identifier') }}"
                                   required>
                            @error('device_identifier')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-500">10 خانات من الأرقام والحروف الإنجليزية فقط</p>
                        </div>

                        <div>
                            <label for="iphone_model" class="block text-sm font-medium text-gray-700 mb-2">
                                طراز الآيفون <span class="text-red-500">*</span>
                            </label>
                            <select id="iphone_model" 
                                    name="iphone_model" 
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('iphone_model') border-red-300 @enderror"
                                    required>
                                <option value="">اختر طراز الآيفون</option>
                                <optgroup label="iPhone 15 Series">
                                    <option value="iPhone 15 Pro Max" {{ old('iphone_model') == 'iPhone 15 Pro Max' ? 'selected' : '' }}>iPhone 15 Pro Max</option>
                                    <option value="iPhone 15 Pro" {{ old('iphone_model') == 'iPhone 15 Pro' ? 'selected' : '' }}>iPhone 15 Pro</option>
                                    <option value="iPhone 15 Plus" {{ old('iphone_model') == 'iPhone 15 Plus' ? 'selected' : '' }}>iPhone 15 Plus</option>
                                    <option value="iPhone 15" {{ old('iphone_model') == 'iPhone 15' ? 'selected' : '' }}>iPhone 15</option>
                                </optgroup>
                                <optgroup label="iPhone 14 Series">
                                    <option value="iPhone 14 Pro Max" {{ old('iphone_model') == 'iPhone 14 Pro Max' ? 'selected' : '' }}>iPhone 14 Pro Max</option>
                                    <option value="iPhone 14 Pro" {{ old('iphone_model') == 'iPhone 14 Pro' ? 'selected' : '' }}>iPhone 14 Pro</option>
                                    <option value="iPhone 14 Plus" {{ old('iphone_model') == 'iPhone 14 Plus' ? 'selected' : '' }}>iPhone 14 Plus</option>
                                    <option value="iPhone 14" {{ old('iphone_model') == 'iPhone 14' ? 'selected' : '' }}>iPhone 14</option>
                                </optgroup>
                                <optgroup label="iPhone 13 Series">
                                    <option value="iPhone 13 Pro Max" {{ old('iphone_model') == 'iPhone 13 Pro Max' ? 'selected' : '' }}>iPhone 13 Pro Max</option>
                                    <option value="iPhone 13 Pro" {{ old('iphone_model') == 'iPhone 13 Pro' ? 'selected' : '' }}>iPhone 13 Pro</option>
                                    <option value="iPhone 13 mini" {{ old('iphone_model') == 'iPhone 13 mini' ? 'selected' : '' }}>iPhone 13 mini</option>
                                    <option value="iPhone 13" {{ old('iphone_model') == 'iPhone 13' ? 'selected' : '' }}>iPhone 13</option>
                                </optgroup>
                                <optgroup label="iPhone 12 Series">
                                    <option value="iPhone 12 Pro Max" {{ old('iphone_model') == 'iPhone 12 Pro Max' ? 'selected' : '' }}>iPhone 12 Pro Max</option>
                                    <option value="iPhone 12 Pro" {{ old('iphone_model') == 'iPhone 12 Pro' ? 'selected' : '' }}>iPhone 12 Pro</option>
                                    <option value="iPhone 12 mini" {{ old('iphone_model') == 'iPhone 12 mini' ? 'selected' : '' }}>iPhone 12 mini</option>
                                    <option value="iPhone 12" {{ old('iphone_model') == 'iPhone 12' ? 'selected' : '' }}>iPhone 12</option>
                                </optgroup>
                                <optgroup label="iPhone 11 Series">
                                    <option value="iPhone 11 Pro Max" {{ old('iphone_model') == 'iPhone 11 Pro Max' ? 'selected' : '' }}>iPhone 11 Pro Max</option>
                                    <option value="iPhone 11 Pro" {{ old('iphone_model') == 'iPhone 11 Pro' ? 'selected' : '' }}>iPhone 11 Pro</option>
                                    <option value="iPhone 11" {{ old('iphone_model') == 'iPhone 11' ? 'selected' : '' }}>iPhone 11</option>
                                </optgroup>
                                <optgroup label="أطرازة أخرى">
                                    <option value="iPhone SE (3rd generation)" {{ old('iphone_model') == 'iPhone SE (3rd generation)' ? 'selected' : '' }}>iPhone SE (3rd generation)</option>
                                    <option value="iPhone XS Max" {{ old('iphone_model') == 'iPhone XS Max' ? 'selected' : '' }}>iPhone XS Max</option>
                                    <option value="iPhone XS" {{ old('iphone_model') == 'iPhone XS' ? 'selected' : '' }}>iPhone XS</option>
                                    <option value="iPhone XR" {{ old('iphone_model') == 'iPhone XR' ? 'selected' : '' }}>iPhone XR</option>
                                    <option value="iPhone X" {{ old('iphone_model') == 'iPhone X' ? 'selected' : '' }}>iPhone X</option>
                                </optgroup>
                            </select>
                            @error('iphone_model')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label for="device_nickname" class="block text-sm font-medium text-gray-700 mb-2">
                            اسم مميز للجهاز (اختياري)
                        </label>
                        <input type="text" 
                               id="device_nickname" 
                               name="device_nickname" 
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('device_nickname') border-red-300 @enderror"
                               placeholder="مثال: جهاز زوجتي، آيفون المكتب، آيفون الشخصي"
                               value="{{ old('device_nickname') }}">
                        @error('device_nickname')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500">اسم يساعدك في التمييز بين أجهزتك المختلفة</p>
                    </div>

                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <div class="mr-3">
                                <h3 class="text-sm font-medium text-blue-800">ملاحظات مهمة</h3>
                                <div class="mt-1 text-sm text-blue-700">
                                    <ul class="list-disc list-inside space-y-1">
                                        <li>سيتم إضافة جهاز الآيفون بحالة "قيد التفعيل" وسيتم مراجعته من قبل الإدارة</li>
                                        <li>ستحصل على رمز التفعيل بعد موافقة الإدارة</li>
                                        <li>تأكد من صحة رقم الجهاز المميز قبل الإضافة</li>
                                        <li>لا يمكن تعديل رقم الجهاز المميز بعد الإضافة</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" 
                                class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded focus:outline-none focus:shadow-outline">
                            إضافة جهاز الآيفون
                        </button>
                    </div>
                </form>
            </div>
        </div>
        @else
        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <div class="mr-3">
                    <h3 class="text-sm font-medium text-yellow-800">الحد الأقصى للأجهزة</h3>
                    <p class="mt-1 text-sm text-yellow-700">
                        لقد وصلت للحد الأقصى المسموح من الأجهزة ({{ $subscription->device_count }}). 
                        لإضافة أجهزة أخرى، يرجى ترقية اشتراكك أو التواصل مع الإدارة.
                    </p>
                </div>
            </div>
        </div>
        @endif

        <!-- Devices List -->
        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 sm:px-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900">
                    الأجهزة المسجلة ({{ $subscription->devices()->count() }})
                </h3>
            </div>
            
            @if($subscription->devices()->count() > 0)
            <div class="border-t border-gray-200">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    معرف الجهاز
                                </th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    طراز الآيفون
                                </th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    الاسم المميز
                                </th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    الحالة
                                </th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    رمز التفعيل
                                </th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    تاريخ التسجيل
                                </th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    آخر اتصال
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($subscription->devices as $device)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-8 w-8">
                                            <div class="h-8 w-8 bg-gray-100 rounded-lg flex items-center justify-center">
                                                <svg class="w-5 h-5 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M7 2a2 2 0 00-2 2v12a2 2 0 002 2h6a2 2 0 002-2V4a2 2 0 00-2-2H7zm3 14a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"></path>
                                                </svg>
                                            </div>
                                        </div>
                                        <div class="mr-3">
                                            <div class="text-sm font-medium text-gray-900">{{ $device->device_identifier }}</div>
                                            <div class="text-xs text-gray-500">{{ $device->device_number }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $device->iphone_model ?: $device->device_version }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        {{ $device->device_nickname ?: 'غير محدد' }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                        @if($device->status == 'active') bg-green-100 text-green-800
                                        @elseif($device->status == 'pending') bg-yellow-100 text-yellow-800
                                        @elseif($device->status == 'suspended') bg-red-100 text-red-800
                                        @elseif($device->status == 'inactive') bg-gray-100 text-gray-800
                                        @endif">
                                        @if($device->status == 'active') نشط
                                        @elseif($device->status == 'pending') قيد التفعيل
                                        @elseif($device->status == 'suspended') معلق
                                        @elseif($device->status == 'inactive') غير نشط
                                        @endif
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    @if($device->activation_token && $device->status == 'active')
                                        <div class="flex items-center space-x-2 space-x-reverse">
                                            <span class="font-mono bg-gray-100 px-2 py-1 rounded text-xs">
                                                {{ $device->activation_token }}
                                            </span>
                                            <button onclick="copyToClipboard('{{ $device->activation_token }}')"
                                                    class="text-blue-600 hover:text-blue-800 text-xs"
                                                    title="نسخ الرمز">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                                </svg>
                                            </button>
                                        </div>
                                    @elseif($device->status == 'pending')
                                        <span class="text-yellow-600 text-xs">قيد الانتظار</span>
                                    @else
                                        <span class="text-gray-400 text-xs">غير متاح</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $device->created_at->format('Y-m-d H:i') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    @if($device->last_connected_at)
                                        <div class="flex items-center">
                                            @if($device->last_connected_at->diffInMinutes(now()) < 5)
                                                <span class="flex h-2 w-2 relative">
                                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                                                    <span class="relative inline-flex rounded-full h-2 w-2 bg-green-500"></span>
                                                </span>
                                                <span class="mr-2 text-xs">متصل الآن</span>
                                            @else
                                                <span class="text-xs">{{ $device->last_connected_at->format('Y-m-d H:i') }}</span>
                                            @endif
                                        </div>
                                    @else
                                        <span class="text-gray-400 text-xs">لم يتصل بعد</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @else
            <div class="border-t border-gray-200 px-4 py-8 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">لا توجد أجهزة مسجلة</h3>
                <p class="mt-1 text-sm text-gray-500">ابدأ بإضافة جهازك الأول لاستخدام الخدمة</p>
            </div>
            @endif
        </div>

        <!-- Instructions -->
        <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-6">
            <h4 class="text-lg font-medium text-blue-900 mb-4">تعليمات استخدام رمز التفعيل</h4>
            <div class="text-sm text-blue-800 space-y-2">
                <p><strong>1. الحصول على الرمز:</strong> بعد تفعيل الجهاز من قبل الإدارة، ستحصل على رمز التفعيل</p>
                <p><strong>2. استخدام الرمز:</strong> أدخل الرمز في تطبيق العميل على جهازك</p>
                <p><strong>3. التفعيل:</strong> سيتم تفعيل الجهاز تلقائياً وستتمكن من استخدام الخدمة</p>
                <p><strong>4. الأمان:</strong> لا تشارك رمز التفعيل مع أي شخص آخر</p>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="mt-8 flex justify-between">
            <a href="{{ route('client.subscriptions.show', $subscription->id) }}" 
               class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                العودة لتفاصيل الاشتراك
            </a>
            
            <a href="{{ route('client.subscriptions') }}" 
               class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                عرض جميع الاشتراكات
            </a>
        </div>
    </div>

    <script>
        function copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(function() {
                // إظهار رسالة تأكيد
                const notification = document.createElement('div');
                notification.className = 'fixed top-4 right-4 bg-green-500 text-white px-4 py-2 rounded shadow-lg z-50';
                notification.textContent = 'تم نسخ رمز التفعيل';
                document.body.appendChild(notification);
                
                // إخفاء الرسالة بعد 3 ثوان
                setTimeout(() => {
                    notification.remove();
                }, 3000);
            }).catch(function(err) {
                console.error('خطأ في نسخ النص: ', err);
            });
        }

        // تحسين تفاعل النموذج
        document.addEventListener('DOMContentLoaded', function() {
            const deviceIdInput = document.getElementById('device_identifier');
            if (deviceIdInput) {
                deviceIdInput.addEventListener('input', function() {
                    // تحويل النص لأحرف كبيرة
                    this.value = this.value.toUpperCase();
                    
                    // إزالة المسافات والرموز غير المسموحة
                    this.value = this.value.replace(/[^A-Z0-9]/g, '');
                    
                    // الحد الأقصى 10 خانات
                    if (this.value.length > 10) {
                        this.value = this.value.substring(0, 10);
                    }
                });
            }
        });
    </script>
</body>
</html>