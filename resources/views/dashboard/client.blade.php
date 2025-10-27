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
        .welcome-message {
            transition: opacity 0.5s ease-in-out, height 0.5s ease-in-out;
        }
        .welcome-message.hidden {
            opacity: 0;
            height: 0;
            margin: 0;
            padding: 0;
            overflow: hidden;
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

        <!-- Welcome Section (Auto-hiding) -->
        <div id="welcomeMessage" class="welcome-message bg-gradient-to-l from-blue-600 to-blue-800 overflow-hidden shadow rounded-lg mb-6">
            <div class="px-4 py-5 sm:p-6 text-white">
                <div class="flex items-center justify-between">
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
                                نتمنى لك تجربة ممتازة مع خدماتنا - ستختفي هذه الرسالة تلقائياً خلال 30 ثانية
                            </p>
                        </div>
                    </div>
                    <button onclick="hideWelcomeMessage()" class="text-white hover:text-blue-200 p-2 rounded-full hover:bg-white hover:bg-opacity-10 transition duration-150">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
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
                                {{ @$accountInfo['subscription_status'] ?? 2 }}
                            </span>
                            <span class="mr-4 text-gray-600">{{ @$accountInfo['subscription_plan'] ?? 'Interprise Plan' }}</span>
                        </div>
                    </div>
                    <div class="text-left">
                        <p class="text-sm text-gray-500">تنتهي في</p>
                        <p class="text-lg font-semibold text-gray-900">{{ @$accountInfo['expires_at'] ?? '155 Days' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Feature Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
            <!-- Subscriptions Card -->
            <a href="{{ route('client.subscriptions') }}" class="bg-white overflow-hidden shadow rounded-lg hover:shadow-lg transition duration-300 transform hover:-translate-y-1">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-blue-500 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="mr-4 flex-1">
                            <h3 class="text-lg font-semibold text-gray-900 mb-1">
                                إدارة الاشتراكات
                            </h3>
                            <p class="text-sm text-gray-500 mb-2">
                                عرض وإدارة اشتراكاتك النشطة
                            </p>
                            <div class="flex items-center text-sm text-blue-600">
                                <span>{{ $stats['active_subscriptions'] ?? 4 }} اشتراك نشط</span>
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
            </a>

            <!-- Devices Card -->
            <a href="{{ route('client.devices') }}" class="bg-white overflow-hidden shadow rounded-lg hover:shadow-lg transition duration-300 transform hover:-translate-y-1">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-green-500 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="mr-4 flex-1">
                            <h3 class="text-lg font-semibold text-gray-900 mb-1">
                                إدارة الأجهزة
                            </h3>
                            <p class="text-sm text-gray-500 mb-2">
                                مراقبة وإدارة أجهزتك المتصلة
                            </p>
                            <div class="flex items-center text-sm text-green-600">
                                <span>{{ @$stats['connected_devices'] ?? 5 }} جهاز متصل</span>
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
            </a>

            <!-- Payments Card -->
            <a href="{{ route('client.payments') }}" class="bg-white overflow-hidden shadow rounded-lg hover:shadow-lg transition duration-300 transform hover:-translate-y-1">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-yellow-500 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="mr-4 flex-1">
                            <h3 class="text-lg font-semibold text-gray-900 mb-1">
                                المدفوعات
                            </h3>
                            <p class="text-sm text-gray-500 mb-2">
                                عرض سجل المدفوعات والفواتير
                            </p>
                            <div class="flex items-center text-sm text-yellow-600">
                                <span>{{ $stats['total_payments'] }} عملية دفع</span>
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
            </a>

            <!-- Statistics Card -->
            <a href="{{ route('client.statistics') }}" class="bg-white overflow-hidden shadow rounded-lg hover:shadow-lg transition duration-300 transform hover:-translate-y-1">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-purple-500 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="mr-4 flex-1">
                            <h3 class="text-lg font-semibold text-gray-900 mb-1">
                                الإحصائيات
                            </h3>
                            <p class="text-sm text-gray-500 mb-2">
                                عرض إحصائيات الاستخدام والتقارير
                            </p>
                            <div class="flex items-center text-sm text-purple-600">
                                <span>{{ @$stats['data_usage'] ?? 70 }}% استخدام البيانات</span>
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <!-- Quick Stats Overview -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
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
                                {{ @$accountInfo['storage_used'] ?? 150 }} / {{ @$accountInfo['storage_limit'] ?? '500' }} GB
                            </dd>
                            <div class="mt-2 w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-blue-600 h-2 rounded-full" style="width: 75%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Account Status -->
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-green-500 rounded-md flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="mr-4 flex-1">
                            <dt class="text-sm font-medium text-gray-500 truncate">
                                حالة الحساب
                            </dt>
                            <dd class="mt-1 text-lg font-semibold text-green-600">
                                نشط
                            </dd>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Next Payment -->
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-yellow-500 rounded-md flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="mr-4 flex-1">
                            <dt class="text-sm font-medium text-gray-500 truncate">
                                الدفعة القادمة
                            </dt>
                            <dd class="mt-1 text-lg font-semibold text-gray-900">
                                {{ $accountInfo['expires_at'] ?? '22-10-2026' }}
                            </dd>
                        </div>
                    </div>
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
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                </div>
                                <div class="mr-4">
                                    <div class="text-sm font-medium text-gray-900">
                                        تم توصيل جهاز جديد
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        تم توصيل جهاز "لابتوب المكتب" بنجاح لحسابك
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
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                        </svg>
                                    </div>
                                </div>
                                <div class="mr-4">
                                    <div class="text-sm font-medium text-gray-900">
                                        تم استلام الدفعة الشهرية
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        تم استلام دفعة بقيمة 150 جنيه مصري لاشتراك شهر يناير
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

    <script>
        // Auto-hide welcome message after 30 seconds
        function hideWelcomeMessage() {
            const welcomeDiv = document.getElementById('welcomeMessage');
            if (welcomeDiv) {
                welcomeDiv.classList.add('hidden');
                
                // Save to localStorage that message was hidden
                localStorage.setItem('welcomeMessageHidden', 'true');
            }
        }

        // Check if welcome message should be hidden on page load
        document.addEventListener('DOMContentLoaded', function() {
            const isHidden = localStorage.getItem('welcomeMessageHidden');
            
            // If not hidden manually, set auto-hide timer
            if (!isHidden) {
                setTimeout(hideWelcomeMessage, 30000); // 30 seconds
            } else {
                // Hide immediately if user previously closed it
                hideWelcomeMessage();
            }
        });
    </script>
</body>
</html>