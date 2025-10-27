<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>الإحصائيات - نظام إدارة الاشتراكات</title>
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
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
                        <h1 class="text-xl font-bold text-purple-600">الإحصائيات والتقارير</h1>
                    </div>
                </div>
                
                <div class="flex items-center space-x-4 space-x-reverse">
                    <div class="flex space-x-2 space-x-reverse">
                        <a href="{{ route('client.dashboard') }}" class="text-gray-600 hover:text-blue-600 px-3 py-2 rounded-md text-sm">
                            لوحة التحكم
                        </a>
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
        <!-- Header -->
        <div class="md:flex md:items-center md:justify-between mb-6">
            <div class="flex-1 min-w-0">
                <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                    الإحصائيات والتقارير
                </h2>
                <p class="mt-1 text-sm text-gray-500">
                    تحليل مفصل لاستخدامك للخدمة وأداء الشبكة
                </p>
            </div>
            <div class="mt-4 flex md:mt-0 md:ml-4">
                <select class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-purple-500 focus:border-purple-500 sm:text-sm rounded-md">
                    <option>آخر 7 أيام</option>
                    <option>آخر 30 يوم</option>
                    <option>آخر 3 شهور</option>
                    <option>آخر سنة</option>
                </select>
            </div>
        </div>

        <!-- Key Statistics -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
            <!-- Data Usage -->
            <div class="bg-gradient-to-br from-blue-500 to-blue-600 overflow-hidden shadow rounded-lg">
                <div class="p-5 text-white">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-white bg-opacity-20 rounded-md flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="mr-4 flex-1">
                            <dt class="text-sm font-medium text-blue-100 truncate">
                                استهلاك البيانات
                            </dt>
                            <dd class="mt-1 text-2xl font-semibold text-white">
                                {{ $statistics['usage']['data_consumed'] }} {{ $statistics['usage']['unit'] }}
                            </dd>
                            <div class="mt-2 w-full bg-white bg-opacity-20 rounded-full h-2">
                                <div class="bg-white h-2 rounded-full" style="width: {{ $statistics['usage']['percentage'] }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Active Devices -->
            <div class="bg-gradient-to-br from-green-500 to-green-600 overflow-hidden shadow rounded-lg">
                <div class="p-5 text-white">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-white bg-opacity-20 rounded-md flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="mr-4 flex-1">
                            <dt class="text-sm font-medium text-green-100 truncate">
                                الأجهزة النشطة
                            </dt>
                            <dd class="mt-1 text-2xl font-semibold text-white">
                                {{ $statistics['devices']['active'] }}/{{ $statistics['devices']['total'] }}
                            </dd>
                            <dd class="text-sm text-green-100">
                                {{ $statistics['devices']['percentage'] }}% نشط
                            </dd>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Uptime -->
            <div class="bg-gradient-to-br from-yellow-500 to-yellow-600 overflow-hidden shadow rounded-lg">
                <div class="p-5 text-white">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-white bg-opacity-20 rounded-md flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="mr-4 flex-1">
                            <dt class="text-sm font-medium text-yellow-100 truncate">
                                وقت التشغيل
                            </dt>
                            <dd class="mt-1 text-2xl font-semibold text-white">
                                {{ $statistics['uptime']['current_month'] }}%
                            </dd>
                            <dd class="text-sm text-yellow-100">
                                هذا الشهر
                            </dd>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Network Speed -->
            <div class="bg-gradient-to-br from-purple-500 to-purple-600 overflow-hidden shadow rounded-lg">
                <div class="p-5 text-white">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-white bg-opacity-20 rounded-md flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="mr-4 flex-1">
                            <dt class="text-sm font-medium text-purple-100 truncate">
                                سرعة التحميل
                            </dt>
                            <dd class="mt-1 text-2xl font-semibold text-white">
                                {{ $statistics['speed']['download'] }}
                            </dd>
                            <dd class="text-sm text-purple-100">
                                {{ $statistics['speed']['unit'] }}
                            </dd>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            <!-- Data Usage Chart -->
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="px-4 py-5 sm:px-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">
                        استهلاك البيانات الشهري
                    </h3>
                    <p class="mt-1 max-w-2xl text-sm text-gray-500">
                        تطور استهلاك البيانات خلال الأشهر الماضية
                    </p>
                </div>
                <div class="px-4 py-5 sm:p-6">
                    <canvas id="dataUsageChart" width="400" height="200"></canvas>
                </div>
            </div>

            <!-- Uptime Chart -->
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="px-4 py-5 sm:px-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">
                        وقت التشغيل الشهري
                    </h3>
                    <p class="mt-1 max-w-2xl text-sm text-gray-500">
                        نسبة وقت التشغيل خلال الأشهر الماضية
                    </p>
                </div>
                <div class="px-4 py-5 sm:p-6">
                    <canvas id="uptimeChart" width="400" height="200"></canvas>
                </div>
            </div>
        </div>

        <!-- Detailed Statistics -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            <!-- Network Performance -->
            <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                <div class="px-4 py-5 sm:px-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">
                        أداء الشبكة
                    </h3>
                    <p class="mt-1 max-w-2xl text-sm text-gray-500">
                        تفاصيل أداء الشبكة والاتصال
                    </p>
                </div>
                <div class="border-t border-gray-200">
                    <dl>
                        <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">
                                سرعة التحميل
                            </dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                <div class="flex items-center">
                                    <span class="ml-2">{{ $statistics['speed']['download'] }} {{ $statistics['speed']['unit'] }}</span>
                                    <div class="flex-1 max-w-xs">
                                        <div class="bg-gray-200 rounded-full h-2">
                                            <div class="bg-blue-600 h-2 rounded-full" style="width: 85%"></div>
                                        </div>
                                    </div>
                                </div>
                            </dd>
                        </div>
                        <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">
                                سرعة الرفع
                            </dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                <div class="flex items-center">
                                    <span class="ml-2">{{ $statistics['speed']['upload'] }} {{ $statistics['speed']['unit'] }}</span>
                                    <div class="flex-1 max-w-xs">
                                        <div class="bg-gray-200 rounded-full h-2">
                                            <div class="bg-green-600 h-2 rounded-full" style="width: 65%"></div>
                                        </div>
                                    </div>
                                </div>
                            </dd>
                        </div>
                        <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">
                                وقت الاستجابة (Ping)
                            </dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                {{ $statistics['speed']['ping'] }} مللي ثانية
                            </dd>
                        </div>
                        <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">
                                استقرار الاتصال
                            </dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    ممتاز ({{ $statistics['uptime']['current_month'] }}%)
                                </span>
                            </dd>
                        </div>
                    </dl>
                </div>
            </div>

            <!-- Usage Breakdown -->
            <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                <div class="px-4 py-5 sm:px-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">
                        تفاصيل الاستخدام
                    </h3>
                    <p class="mt-1 max-w-2xl text-sm text-gray-500">
                        تحليل مفصل لنوع البيانات المستخدمة
                    </p>
                </div>
                <div class="border-t border-gray-200">
                    <dl>
                        <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">
                                تصفح الويب
                            </dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                <div class="flex items-center justify-between">
                                    <span>12.5 جيجابايت</span>
                                    <span class="text-sm text-gray-500">28%</span>
                                </div>
                                <div class="mt-1 w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-blue-600 h-2 rounded-full" style="width: 28%"></div>
                                </div>
                            </dd>
                        </div>
                        <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">
                                مقاطع الفيديو
                            </dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                <div class="flex items-center justify-between">
                                    <span>22.1 جيجابايت</span>
                                    <span class="text-sm text-gray-500">49%</span>
                                </div>
                                <div class="mt-1 w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-red-600 h-2 rounded-full" style="width: 49%"></div>
                                </div>
                            </dd>
                        </div>
                        <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">
                                التطبيقات
                            </dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                <div class="flex items-center justify-between">
                                    <span>7.8 جيجابايت</span>
                                    <span class="text-sm text-gray-500">17%</span>
                                </div>
                                <div class="mt-1 w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-green-600 h-2 rounded-full" style="width: 17%"></div>
                                </div>
                            </dd>
                        </div>
                        <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">
                                أخرى
                            </dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                <div class="flex items-center justify-between">
                                    <span>2.8 جيجابايت</span>
                                    <span class="text-sm text-gray-500">6%</span>
                                </div>
                                <div class="mt-1 w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-purple-600 h-2 rounded-full" style="width: 6%"></div>
                                </div>
                            </dd>
                        </div>
                    </dl>
                </div>
            </div>
        </div>

        <!-- Export Options -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-4 py-5 sm:px-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900">
                    تصدير التقارير
                </h3>
                <p class="mt-1 max-w-2xl text-sm text-gray-500">
                    تحميل التقارير بصيغ مختلفة للمراجعة أو الأرشفة
                </p>
            </div>
            <div class="px-4 py-5 sm:p-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <button class="flex items-center justify-center px-4 py-2 border border-gray-300 rounded-md shadow-sm bg-white text-sm font-medium text-gray-700 hover:bg-gray-50">
                        <svg class="w-5 h-5 ml-2 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                        </svg>
                        تصدير PDF
                    </button>
                    <button class="flex items-center justify-center px-4 py-2 border border-gray-300 rounded-md shadow-sm bg-white text-sm font-medium text-gray-700 hover:bg-gray-50">
                        <svg class="w-5 h-5 ml-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        تصدير Excel
                    </button>
                    <button class="flex items-center justify-center px-4 py-2 border border-gray-300 rounded-md shadow-sm bg-white text-sm font-medium text-gray-700 hover:bg-gray-50">
                        <svg class="w-5 h-5 ml-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                        </svg>
                        تصدير CSV
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Data Usage Chart
        const dataUsageCtx = document.getElementById('dataUsageChart').getContext('2d');
        const dataUsageChart = new Chart(dataUsageCtx, {
            type: 'line',
            data: {
                labels: {!! json_encode($monthlyData['labels']) !!},
                datasets: [{
                    label: 'استهلاك البيانات (جيجابايت)',
                    data: {!! json_encode($monthlyData['data_usage']) !!},
                    borderColor: 'rgb(59, 130, 246)',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    tension: 0.1,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return value + ' جيجابايت';
                            }
                        }
                    }
                }
            }
        });

        // Uptime Chart
        const uptimeCtx = document.getElementById('uptimeChart').getContext('2d');
        const uptimeChart = new Chart(uptimeCtx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($monthlyData['labels']) !!},
                datasets: [{
                    label: 'وقت التشغيل (%)',
                    data: {!! json_encode($monthlyData['uptime']) !!},
                    backgroundColor: 'rgba(34, 197, 94, 0.8)',
                    borderColor: 'rgb(34, 197, 94)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 100,
                        ticks: {
                            callback: function(value) {
                                return value + '%';
                            }
                        }
                    }
                }
            }
        });
    </script>
</body>
</html>