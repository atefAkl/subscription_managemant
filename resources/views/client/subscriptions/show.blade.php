<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>تفاصيل الاشتراك - نظام إدارة الاشتراكات</title>
    
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
                        <h1 class="text-xl font-bold text-blue-600">تفاصيل الاشتراك</h1>
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
        <!-- Subscription Header -->
        <div class="bg-white overflow-hidden shadow rounded-lg mb-6">
            <div class="px-4 py-5 sm:p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">{{ $subscription->name }}</h2>
                        <p class="mt-1 text-sm text-gray-500">
                            اشتراك رقم #{{ $subscription->id }} - نشط منذ {{ $subscription->start_date->format('Y-m-d') }}
                        </p>
                    </div>
                    <div class="text-center">
                        <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium 
                            @if($subscription->status == 'active') bg-green-100 text-green-800
                            @elseif($subscription->status == 'expired') bg-red-100 text-red-800
                            @elseif($subscription->status == 'suspended') bg-yellow-100 text-yellow-800
                            @endif">
                            @if($subscription->status == 'active') نشط
                            @elseif($subscription->status == 'expired') منتهي الصلاحية
                            @elseif($subscription->status == 'suspended') معلق
                            @endif
                        </span>
                        <p class="mt-2 text-lg font-semibold text-gray-900">{{ number_format($subscription->price, 2) }} جنيه/شهر</p>
                    </div>
                </div>

                @if($subscription->end_date && $subscription->end_date->diffInDays(now()) <= 7)
                <div class="mt-4 bg-orange-50 border border-orange-200 rounded-lg p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-orange-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <div class="mr-3">
                            <h3 class="text-sm font-medium text-orange-800">تنبيه انتهاء الصلاحية</h3>
                            <p class="mt-1 text-sm text-orange-700">
                                سينتهي الاشتراك في {{ $subscription->end_date->format('Y-m-d') }} 
                                ({{ $subscription->end_date->diffInDays(now()) }} {{ $subscription->end_date->diffInDays(now()) == 1 ? 'يوم' : 'أيام' }})
                            </p>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Subscription Details -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            <!-- Subscription Info -->
            <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                <div class="px-4 py-5 sm:px-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">
                        معلومات الاشتراك
                    </h3>
                </div>
                <div class="border-t border-gray-200">
                    <dl>
                        <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">اسم الاشتراك</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $subscription->name }}</dd>
                        </div>
                        <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">عدد الأجهزة المسموحة</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $subscription->device_count }} أجهزة</dd>
                        </div>
                        <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">تاريخ البداية</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $subscription->start_date->format('Y-m-d') }}</dd>
                        </div>
                        <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">تاريخ الانتهاء</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                {{ $subscription->end_date ? $subscription->end_date->format('Y-m-d') : 'غير محدد' }}
                            </dd>
                        </div>
                        @if($subscription->description)
                        <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">الوصف</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $subscription->description }}</dd>
                        </div>
                        @endif
                    </dl>
                </div>
            </div>

            <!-- Device Summary -->
            <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                <div class="px-4 py-5 sm:px-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">
                        ملخص الأجهزة
                    </h3>
                </div>
                <div class="border-t border-gray-200 px-4 py-5 sm:p-6">
                    <div class="grid grid-cols-1 gap-4">
                        <div class="bg-green-50 p-4 rounded-lg">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <svg class="h-6 w-6 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <div class="mr-3">
                                    <p class="text-sm font-medium text-green-800">الأجهزة النشطة</p>
                                    <p class="text-2xl font-bold text-green-900">{{ $subscription->devices()->where('status', 'active')->count() }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="bg-yellow-50 p-4 rounded-lg">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <svg class="h-6 w-6 text-yellow-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <div class="mr-3">
                                    <p class="text-sm font-medium text-yellow-800">قيد التفعيل</p>
                                    <p class="text-2xl font-bold text-yellow-900">{{ $subscription->devices()->where('status', 'pending')->count() }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="bg-gray-50 p-4 rounded-lg">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <svg class="h-6 w-6 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                </div>
                                <div class="mr-3">
                                    <p class="text-sm font-medium text-gray-800">متاح للإضافة</p>
                                    <p class="text-2xl font-bold text-gray-900">{{ $subscription->device_count - $subscription->devices()->count() }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Devices List -->
        <div class="bg-white shadow overflow-hidden sm:rounded-lg mb-6">
            <div class="px-4 py-5 sm:px-6 flex justify-between items-center">
                <h3 class="text-lg leading-6 font-medium text-gray-900">
                    الأجهزة المسجلة ({{ $subscription->devices()->count() }}/{{ $subscription->device_count }})
                </h3>
                @if($subscription->device_count > $subscription->devices()->count())
                <a href="{{ route('client.subscriptions.devices', $subscription->id) }}" 
                   class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded text-sm font-medium">
                    إضافة جهاز جديد
                </a>
                @endif
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
                                    تاريخ التسجيل
                                </th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    آخر اتصال
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($subscription->devices as $device)
                            <tr>
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
                                            <div class="text-sm font-medium text-gray-900">{{ $device->device_identifier ?: $device->device_number }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $device->iphone_model ?: $device->device_version }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $device->device_nickname ?: 'غير محدد' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                        @if($device->status == 'active') bg-green-100 text-green-800
                                        @elseif($device->status == 'pending') bg-yellow-100 text-yellow-800
                                        @elseif($device->status == 'suspended') bg-red-100 text-red-800
                                        @endif">
                                        @if($device->status == 'active') نشط
                                        @elseif($device->status == 'pending') قيد التفعيل
                                        @elseif($device->status == 'suspended') معلق
                                        @endif
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $device->created_at->format('Y-m-d') }}
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
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">لا توجد أجهزة مسجلة</h3>
                <p class="mt-1 text-sm text-gray-500">ابدأ بإضافة جهازك الأول</p>
                <div class="mt-6">
                    <a href="{{ route('client.subscriptions.devices', $subscription->id) }}" 
                       class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                        إضافة جهاز
                    </a>
                </div>
            </div>
            @endif
        </div>

        <!-- Action Buttons -->
        <div class="flex justify-between">
            <a href="{{ route('client.subscriptions') }}" 
               class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                العودة للاشتراكات
            </a>
            
            <div class="space-x-2 space-x-reverse">
                @if($subscription->device_count > $subscription->devices()->count())
                <a href="{{ route('client.subscriptions.devices', $subscription->id) }}" 
                   class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    إدارة الأجهزة
                </a>
                @endif
            </div>
        </div>
    </div>
</body>
</html>