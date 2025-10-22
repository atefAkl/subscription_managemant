<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>لوحة العميل - نظام إدارة الاشتراكات</title>
    
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
                        <h1 class="text-xl font-bold text-blue-600">لوحة العميل</h1>
                    </div>
                </div>
                
                <div class="flex items-center space-x-4 space-x-reverse">
                    <div class="relative">
                        <span class="text-gray-700">مرحباً، {{ $user->name }}</span>
                        <span class="inline-block bg-green-100 text-green-800 text-xs px-2 py-1 rounded-full mr-2">عميل</span>
                    </div>
                    
                    <div class="flex space-x-2 space-x-reverse">
                        <a href="{{ route('home') }}" class="text-gray-600 hover:text-blue-600 px-3 py-2 rounded-md text-sm">
                            الصفحة الرئيسية
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
    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        @if(session('success'))
            <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                {{ session('error') }}
            </div>
        @endif

        <!-- Welcome Section -->
        <div class="bg-gradient-to-l from-blue-600 to-blue-800 overflow-hidden shadow rounded-lg mb-6">
            <div class="px-4 py-5 sm:p-6 text-white">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="mr-4">
                        <h3 class="text-lg leading-6 font-medium">
                            مرحباً بك، {{ $user->name }}
                        </h3>
                        <p class="mt-1 text-sm text-blue-100">
                            نتمنى لك تجربة ممتازة مع خدماتنا
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Account Status Card -->
        <div class="bg-white overflow-hidden shadow rounded-lg mb-6">
            <div class="px-4 py-5 sm:p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-2">
                            حالة الاشتراك
                        </h3>
                        <div class="flex items-center">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                <svg class="w-4 h-4 ml-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                {{ $accountInfo['subscription_status'] }}
                            </span>
                            <span class="mr-4 text-gray-600">{{ $accountInfo['subscription_plan'] }}</span>
                        </div>
                    </div>
                    <div class="text-left">
                        <p class="text-sm text-gray-500">تنتهي في</p>
                        <p class="text-lg font-semibold text-gray-900">{{ $accountInfo['expires_at'] }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
            <!-- Storage Usage -->
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-blue-500 rounded-md flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4V2a1 1 0 011-1h8a1 1 0 011 1v2h4a1 1 0 011 1v1a1 1 0 01-1 1v9a2 2 0 01-2 2H5a2 2 0 01-2-2V7a1 1 0 01-1-1V5a1 1 0 011-1h4z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="mr-4 flex-1">
                            <dt class="text-sm font-medium text-gray-500 truncate">
                                استخدام التخزين
                            </dt>
                            <dd class="mt-1 text-lg font-semibold text-gray-900">
                                {{ $accountInfo['storage_used'] }} / {{ $accountInfo['storage_limit'] }}
                            </dd>
                            <div class="mt-2 w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-blue-600 h-2 rounded-full" style="width: 75%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Projects Count -->
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-green-500 rounded-md flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="mr-4 flex-1">
                            <dt class="text-sm font-medium text-gray-500 truncate">
                                المشاريع النشطة
                            </dt>
                            <dd class="mt-1 text-2xl font-semibold text-gray-900">
                                5
                            </dd>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Support Tickets -->
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-yellow-500 rounded-md flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192L5.636 18.364M12 12h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="mr-4 flex-1">
                            <dt class="text-sm font-medium text-gray-500 truncate">
                                تذاكر الدعم
                            </dt>
                            <dd class="mt-1 text-2xl font-semibold text-gray-900">
                                2
                            </dd>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-white shadow overflow-hidden sm:rounded-md mb-6">
            <div class="px-4 py-5 sm:px-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900">
                    الإجراءات السريعة
                </h3>
                <p class="mt-1 max-w-2xl text-sm text-gray-500">
                    الوصول السريع للمهام الأكثر استخداماً
                </p>
            </div>
            <div class="px-4 py-5 sm:p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <button class="flex items-center p-4 bg-gray-50 hover:bg-gray-100 rounded-lg transition duration-150 ease-in-out">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-blue-500 rounded-md flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="mr-3">
                            <p class="text-sm font-medium text-gray-900">مشروع جديد</p>
                            <p class="text-sm text-gray-500">إنشاء مشروع جديد</p>
                        </div>
                    </button>

                    <button class="flex items-center p-4 bg-gray-50 hover:bg-gray-100 rounded-lg transition duration-150 ease-in-out">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-green-500 rounded-md flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="mr-3">
                            <p class="text-sm font-medium text-gray-900">التقارير</p>
                            <p class="text-sm text-gray-500">عرض التقارير</p>
                        </div>
                    </button>

                    <button class="flex items-center p-4 bg-gray-50 hover:bg-gray-100 rounded-lg transition duration-150 ease-in-out">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-yellow-500 rounded-md flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192L5.636 18.364M12 12h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="mr-3">
                            <p class="text-sm font-medium text-gray-900">الدعم الفني</p>
                            <p class="text-sm text-gray-500">طلب المساعدة</p>
                        </div>
                    </button>

                    <button class="flex items-center p-4 bg-gray-50 hover:bg-gray-100 rounded-lg transition duration-150 ease-in-out">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-purple-500 rounded-md flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="mr-3">
                            <p class="text-sm font-medium text-gray-900">الإعدادات</p>
                            <p class="text-sm text-gray-500">إدارة الحساب</p>
                        </div>
                    </button>
                </div>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="bg-white shadow overflow-hidden sm:rounded-md">
            <div class="px-4 py-5 sm:px-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900">
                    النشاط الأخير
                </h3>
                <p class="mt-1 max-w-2xl text-sm text-gray-500">
                    آخر العمليات والأنشطة في حسابك
                </p>
            </div>
            <ul class="divide-y divide-gray-200">
                <li>
                    <div class="px-4 py-4 sm:px-6">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10">
                                    <div class="h-10 w-10 rounded-full bg-green-100 flex items-center justify-center">
                                        <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                </div>
                                <div class="mr-4">
                                    <div class="text-sm font-medium text-gray-900">
                                        تم تجديد الاشتراك بنجاح
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        تم تجديد اشتراكك في الباقة المتقدمة لمدة شهر إضافي
                                    </div>
                                </div>
                            </div>
                            <div class="text-sm text-gray-500">
                                منذ يومين
                            </div>
                        </div>
                    </div>
                </li>

                <li>
                    <div class="px-4 py-4 sm:px-6">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10">
                                    <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                        </svg>
                                    </div>
                                </div>
                                <div class="mr-4">
                                    <div class="text-sm font-medium text-gray-900">
                                        إنشاء مشروع جديد
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        تم إنشاء مشروع "موقع الشركة الجديد" بنجاح
                                    </div>
                                </div>
                            </div>
                            <div class="text-sm text-gray-500">
                                منذ 3 أيام
                            </div>
                        </div>
                    </div>
                </li>

                <li>
                    <div class="px-4 py-4 sm:px-6">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10">
                                    <div class="h-10 w-10 rounded-full bg-yellow-100 flex items-center justify-center">
                                        <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192L5.636 18.364M12 12h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                </div>
                                <div class="mr-4">
                                    <div class="text-sm font-medium text-gray-900">
                                        طلب دعم فني
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        تم إرسال تذكرة دعم رقم #1234 وهي قيد المراجعة
                                    </div>
                                </div>
                            </div>
                            <div class="text-sm text-gray-500">
                                منذ أسبوع
                            </div>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</body>
</html>