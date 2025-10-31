<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>الإحصائيات والتقارير - إدارة الاشتراكات</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: 'Cairo', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">
    <!-- Header -->
    <header class="bg-white shadow-sm border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-4">
                <div class="flex items-center space-x-4 space-x-reverse">
                    <a href="{{ route('admin.dashboard') }}" class="text-blue-600 hover:text-blue-800">
                        <i class="fas fa-arrow-right ml-2"></i>
                        العودة للوحة التحكم
                    </a>
                    <h1 class="text-2xl font-bold text-gray-900">الإحصائيات والتقارير</h1>
                </div>
                
                <div class="flex items-center space-x-3 space-x-reverse">
                    <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm">
                        <i class="fas fa-sync ml-1"></i>
                        تحديث البيانات
                    </button>
                    <button class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md text-sm">
                        <i class="fas fa-download ml-1"></i>
                        تصدير التقرير
                    </button>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        
        <!-- Quick Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-lg p-6 shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-blue-100 text-sm">إجمالي العملاء</p>
                        <p class="text-3xl font-bold">1,247</p>
                        <p class="text-blue-100 text-xs mt-1">
                            <i class="fas fa-arrow-up ml-1"></i>
                            +12% من الشهر الماضي
                        </p>
                    </div>
                    <div class="w-16 h-16 bg-blue-400 bg-opacity-30 rounded-full flex items-center justify-center">
                        <i class="fas fa-users text-2xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-r from-green-500 to-green-600 text-white rounded-lg p-6 shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-green-100 text-sm">الإيرادات الشهرية</p>
                        <p class="text-3xl font-bold">45,230</p>
                        <p class="text-green-100 text-xs mt-1">
                            <i class="fas fa-arrow-up ml-1"></i>
                            +8% من الشهر الماضي
                        </p>
                    </div>
                    <div class="w-16 h-16 bg-green-400 bg-opacity-30 rounded-full flex items-center justify-center">
                        <i class="fas fa-money-bill-wave text-2xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-r from-purple-500 to-purple-600 text-white rounded-lg p-6 shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-purple-100 text-sm">الاشتراكات النشطة</p>
                        <p class="text-3xl font-bold">892</p>
                        <p class="text-purple-100 text-xs mt-1">
                            <i class="fas fa-arrow-up ml-1"></i>
                            +15% من الشهر الماضي
                        </p>
                    </div>
                    <div class="w-16 h-16 bg-purple-400 bg-opacity-30 rounded-full flex items-center justify-center">
                        <i class="fas fa-star text-2xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-r from-orange-500 to-orange-600 text-white rounded-lg p-6 shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-orange-100 text-sm">الأجهزة المتصلة</p>
                        <p class="text-3xl font-bold">3,456</p>
                        <p class="text-orange-100 text-xs mt-1">
                            <i class="fas fa-arrow-up ml-1"></i>
                            +23% من الشهر الماضي
                        </p>
                    </div>
                    <div class="w-16 h-16 bg-orange-400 bg-opacity-30 rounded-full flex items-center justify-center">
                        <i class="fas fa-mobile-alt text-2xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            
            <!-- Revenue Chart -->
            <div class="bg-white rounded-lg shadow-lg p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">
                        <i class="fas fa-chart-line text-blue-600 ml-2"></i>
                        الإيرادات الشهرية
                    </h3>
                    <select class="border border-gray-300 rounded-md px-3 py-1 text-sm">
                        <option>آخر 6 أشهر</option>
                        <option>آخر سنة</option>
                        <option>كل البيانات</option>
                    </select>
                </div>
                <div style="position: relative; height: 300px;">
                    <canvas id="revenueChart"></canvas>
                </div>
            </div>

            <!-- Subscriptions Chart -->
            <div class="bg-white rounded-lg shadow-lg p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">
                        <i class="fas fa-chart-pie text-green-600 ml-2"></i>
                        توزيع الاشتراكات
                    </h3>
                    <select class="border border-gray-300 rounded-md px-3 py-1 text-sm">
                        <option>الشهر الحالي</option>
                        <option>الشهر الماضي</option>
                        <option>آخر 3 أشهر</option>
                    </select>
                </div>
                <div style="position: relative; height: 300px;">
                    <canvas id="subscriptionsChart"></canvas>
                </div>
            </div>

        </div>

        <!-- Detailed Tables -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            
            <!-- Top Clients -->
            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                <div class="px-6 py-4 bg-gradient-to-r from-indigo-500 to-purple-600">
                    <h3 class="text-lg font-medium text-white flex items-center">
                        <i class="fas fa-trophy ml-2"></i>
                        أفضل العملاء
                    </h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">العميل</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">الإيرادات</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">الأجهزة</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center text-white text-sm font-bold">
                                            م
                                        </div>
                                        <div class="mr-3">
                                            <div class="text-sm font-medium text-gray-900">محمد أحمد</div>
                                            <div class="text-sm text-gray-500">mohammed@test.com</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-semibold">2,340 ج.م</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">15 جهاز</td>
                            </tr>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center text-white text-sm font-bold">
                                            س
                                        </div>
                                        <div class="mr-3">
                                            <div class="text-sm font-medium text-gray-900">سارة علي</div>
                                            <div class="text-sm text-gray-500">sara@test.com</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-semibold">1,890 ج.م</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">12 جهاز</td>
                            </tr>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 bg-purple-500 rounded-full flex items-center justify-center text-white text-sm font-bold">
                                            أ
                                        </div>
                                        <div class="mr-3">
                                            <div class="text-sm font-medium text-gray-900">أحمد حسن</div>
                                            <div class="text-sm text-gray-500">ahmed@test.com</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-semibold">1,650 ج.م</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">8 أجهزة</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Recent Activities -->
            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                <div class="px-6 py-4 bg-gradient-to-r from-teal-500 to-cyan-600">
                    <h3 class="text-lg font-medium text-white flex items-center">
                        <i class="fas fa-activity ml-2"></i>
                        النشاطات الحديثة
                    </h3>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <div class="flex items-start">
                            <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-plus text-green-600 text-sm"></i>
                            </div>
                            <div class="mr-3 flex-1">
                                <p class="text-sm font-medium text-gray-900">عميل جديد مسجل</p>
                                <p class="text-sm text-gray-500">محمد عبدالله انضم للنظام</p>
                                <p class="text-xs text-gray-400 mt-1">منذ 5 دقائق</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start">
                            <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-credit-card text-blue-600 text-sm"></i>
                            </div>
                            <div class="mr-3 flex-1">
                                <p class="text-sm font-medium text-gray-900">دفعة جديدة</p>
                                <p class="text-sm text-gray-500">تم استلام دفعة بقيمة 500 ج.م</p>
                                <p class="text-xs text-gray-400 mt-1">منذ 15 دقيقة</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start">
                            <div class="w-8 h-8 bg-yellow-100 rounded-full flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-mobile-alt text-yellow-600 text-sm"></i>
                            </div>
                            <div class="mr-3 flex-1">
                                <p class="text-sm font-medium text-gray-900">جهاز جديد مفعل</p>
                                <p class="text-sm text-gray-500">iPhone 15 Pro تم تفعيله</p>
                                <p class="text-xs text-gray-400 mt-1">منذ 30 دقيقة</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start">
                            <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-exclamation-triangle text-red-600 text-sm"></i>
                            </div>
                            <div class="mr-3 flex-1">
                                <p class="text-sm font-medium text-gray-900">اشتراك منتهي الصلاحية</p>
                                <p class="text-sm text-gray-500">اشتراك العميل "أحمد محمد" انتهى</p>
                                <p class="text-xs text-gray-400 mt-1">منذ ساعة</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </main>

    <script>
        // Setup CSRF token for all AJAX requests
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // Revenue Chart
        const revenueCtx = document.getElementById('revenueChart').getContext('2d');
        new Chart(revenueCtx, {
            type: 'line',
            data: {
                labels: ['يناير', 'فبراير', 'مارس', 'أبريل', 'مايو', 'يونيو'],
                datasets: [{
                    label: 'الإيرادات (ج.م)',
                    data: [12000, 19000, 15000, 25000, 22000, 30000],
                    borderColor: 'rgb(59, 130, 246)',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    tension: 0.1,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
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
                                return value + ' ج.م';
                            }
                        }
                    }
                }
            }
        });

        // Subscriptions Pie Chart
        const subscriptionsCtx = document.getElementById('subscriptionsChart').getContext('2d');
        new Chart(subscriptionsCtx, {
            type: 'doughnut',
            data: {
                labels: ['اشتراك أساسي', 'اشتراك متميز', 'اشتراك احترافي'],
                datasets: [{
                    data: [45, 35, 20],
                    backgroundColor: [
                        'rgb(34, 197, 94)',
                        'rgb(59, 130, 246)',
                        'rgb(168, 85, 247)'
                    ],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });

        // Auto refresh every 30 seconds
        setInterval(() => {
            // Update statistics
            console.log('Refreshing statistics...');
        }, 30000);
    </script>
</body>
</html>