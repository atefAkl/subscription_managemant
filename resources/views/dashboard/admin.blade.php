<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>لوحة الإدارة - إدارة الاشتراكات</title>
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Font -->
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700&display=swap" rel="stylesheet">
    
    <!-- Custom Styles -->
    <link href="{{ asset('css/custom-styles.css') }}" rel="stylesheet">
    
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
                        <h1 class="text-xl font-bold text-blue-600">لوحة إدارة النظام</h1>
                    </div>
                </div>
                
                <div class="flex items-center space-x-4 space-x-reverse">
                    <div class="relative">
                        <span class="text-gray-700">مرحباً، {{ $user->name }}</span>
                        <span class="inline-block bg-red-100 text-red-800 text-xs px-2 py-1 rounded-full mr-2">مدير</span>
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
        <div class="bg-gradient-to-r from-blue-600 to-purple-600 overflow-hidden shadow-xl rounded-lg mb-8">
            <div class="px-6 py-8 sm:p-10 text-white">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-16 h-16 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-4m-5 0H3m2 0h3M9 7h6m-6 4h6m-2 4h2M9 15h2"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="mr-6">
                        <h1 class="text-3xl font-bold">
                            مرحباً بك في لوحة الإدارة المتقدمة
                        </h1>
                        <p class="mt-2 text-blue-100 text-lg">
                            تحكم كامل في النظام من خلال أربع وحدات إدارية متخصصة
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Management Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-6 mb-8">
            <!-- Card 1: User Management -->
            <a href="{{ route('admin.users.index') }}" class="bg-white rounded-lg shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 card-compact">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-users text-blue-600 text-xl"></i>
                        </div>
                        <div class="text-right">
                            <div class="text-2xl font-bold text-blue-600">{{ $totalAdmins ?? 0 }}</div>
                            <div class="text-xs text-gray-500">مديرين</div>
                        </div>
                    </div>
                    <h3 class="font-bold text-gray-900 mb-1">إدارة المستخدمين</h3>
                    <p class="text-gray-600 text-xs">إدارة المديرين والصلاحيات</p>
                </div>
            </a>

            <!-- Card 2: Client Management -->
            <a href="{{ route('admin.clients.index') }}" class="bg-white rounded-lg shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 card-compact">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-building text-green-600 text-xl"></i>
                        </div>
                        <div class="text-right">
                            <div class="text-2xl font-bold text-green-600">{{ $totalClients ?? 0 }}</div>
                            <div class="text-xs text-gray-500">عملاء</div>
                        </div>
                    </div>
                    <h3 class="font-bold text-gray-900 mb-1">إدارة العملاء</h3>
                    <p class="text-gray-600 text-xs">النشطين والجدد</p>
                </div>
            </a>

            <!-- Card 3: Subscriptions Management -->
            <a href="{{ route('admin.subscriptions.index') }}" class="bg-white rounded-lg shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 card-compact">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 bg-indigo-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-star text-indigo-600 text-xl"></i>
                        </div>
                        <div class="text-right">
                            <div class="text-2xl font-bold text-indigo-600">{{ $subscriptionStats['active_subscriptions'] ?? 0 }}</div>
                            <div class="text-xs text-gray-500">اشتراكات</div>
                        </div>
                    </div>
                    <h3 class="font-bold text-gray-900 mb-1">إدارة الاشتراكات</h3>
                    <p class="text-gray-600 text-xs">تفعيل وإدارة الخطط</p>
                </div>
            </a>

            <!-- Card 4: Devices Management -->
            <a href="{{ route('admin.devices.index') }}" class="bg-white rounded-lg shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 card-compact">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 bg-teal-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-mobile-alt text-teal-600 text-xl"></i>
                        </div>
                        <div class="text-right">
                            <div class="text-2xl font-bold text-teal-600">{{ $subscriptionStats['total_devices'] ?? 0 }}</div>
                            <div class="text-xs text-gray-500">أجهزة</div>
                        </div>
                    </div>
                    <h3 class="font-bold text-gray-900 mb-1">إدارة الأجهزة</h3>
                    <p class="text-gray-600 text-xs">تفعيل وصيانة الأجهزة</p>
                </div>
            </a>

            <!-- Card 5: System Settings -->
            <a href="{{ route('admin.settings.index') }}" class="bg-white rounded-lg shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 card-compact">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-cogs text-purple-600 text-xl"></i>
                        </div>
                        <div class="text-right">
                            <div class="text-2xl font-bold text-purple-600">6</div>
                            <div class="text-xs text-gray-500">إعدادات</div>
                        </div>
                    </div>
                    <h3 class="font-bold text-gray-900 mb-1">إعدادات النظام</h3>
                    <p class="text-gray-600 text-xs">التكوين والأمان</p>
                </div>
            </a>

            <!-- Card 6: Statistics -->
            <a href="{{ route('admin.statistics.index') }}" class="bg-white rounded-lg shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 card-compact">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-chart-bar text-orange-600 text-xl"></i>
                        </div>
                        <div class="text-right">
                            <div class="text-2xl font-bold text-orange-600">12</div>
                            <div class="text-xs text-gray-500">تقارير</div>
                        </div>
                    </div>
                    <h3 class="font-bold text-gray-900 mb-1">الإحصائيات</h3>
                    <p class="text-gray-600 text-xs">التقارير والأداء</p>
                </div>
            </a>
        </div>

        <!-- Quick Statistics Overview -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6 mb-8">
            <!-- Pending Requests -->
            <div class="bg-white overflow-hidden shadow-lg rounded-lg border-t-4 border-yellow-500">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-clock text-yellow-600"></i>
                            </div>
                        </div>
                        <div class="mr-4 flex-1">
                            <dt class="text-sm font-medium text-gray-500">
                                طلبات معلقة
                            </dt>
                            <dd class="mt-1 text-3xl font-bold text-gray-900">
                                {{ $subscriptionStats['pending_requests'] ?? 0 }}
                            </dd>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Active Devices -->
            <div class="bg-white overflow-hidden shadow-lg rounded-lg border-t-4 border-green-500">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-mobile-alt text-green-600"></i>
                            </div>
                        </div>
                        <div class="mr-4 flex-1">
                            <dt class="text-sm font-medium text-gray-500">
                                أجهزة نشطة
                            </dt>
                            <dd class="mt-1 text-3xl font-bold text-gray-900">
                                {{ $subscriptionStats['active_devices'] ?? 0 }}
                            </dd>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pending Devices -->
            <div class="bg-white overflow-hidden shadow-lg rounded-lg border-t-4 border-blue-500">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-sync-alt text-blue-600"></i>
                            </div>
                        </div>
                        <div class="mr-4 flex-1">
                            <dt class="text-sm font-medium text-gray-500">
                                أجهزة معلقة
                            </dt>
                            <dd class="mt-1 text-3xl font-bold text-gray-900">
                                {{ $subscriptionStats['pending_devices'] ?? 0 }}
                            </dd>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pending Payments -->
            <div class="bg-white overflow-hidden shadow-lg rounded-lg border-t-4 border-red-500">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-credit-card text-red-600"></i>
                            </div>
                        </div>
                        <div class="mr-4 flex-1">
                            <dt class="text-sm font-medium text-gray-500">
                                مدفوعات معلقة
                            </dt>
                            <dd class="mt-1 text-3xl font-bold text-gray-900">
                                {{ $subscriptionStats['pending_payments'] ?? 0 }}
                            </dd>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Admin Count -->
            <div class="bg-white overflow-hidden shadow-lg rounded-lg border-t-4 border-purple-500">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-user-tie text-purple-600"></i>
                            </div>
                        </div>
                        <div class="mr-4 flex-1">
                            <dt class="text-sm font-medium text-gray-500">
                                المديرون
                            </dt>
                            <dd class="mt-1 text-3xl font-bold text-gray-900">
                                {{ $totalAdmins ?? 0 }}
                            </dd>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Payments Management Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- Payment Statistics -->
            <div class="bg-white shadow-xl rounded-lg overflow-hidden">
                <div class="px-6 py-4 bg-gradient-to-r from-green-500 to-blue-600">
                    <h3 class="text-lg font-medium text-white flex items-center">
                        <i class="fas fa-money-bill-wave ml-2"></i>
                        إحصائيات المدفوعات
                    </h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-2 gap-4">
                        <div class="text-center">
                            <div class="text-2xl font-bold text-green-600">{{ $paymentStats['verified_payments'] ?? 0 }}</div>
                            <div class="text-sm text-gray-500">مدفوعات مؤكدة</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-yellow-600">{{ $paymentStats['pending_payments'] ?? 0 }}</div>
                            <div class="text-sm text-gray-500">بانتظار التحقق</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-red-600">{{ $paymentStats['rejected_payments'] ?? 0 }}</div>
                            <div class="text-sm text-gray-500">مدفوعات مرفوضة</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-blue-600">{{ number_format($paymentStats['total_amount_today'] ?? 0) }}</div>
                            <div class="text-sm text-gray-500">إجمالي اليوم (ج.م)</div>
                        </div>
                    </div>
                    <div class="mt-4 pt-4 border-t">
                        <div class="flex justify-between items-center">
                            <div>
                                <div class="text-lg font-semibold text-gray-800">
                                    {{ number_format($paymentStats['total_amount_pending'] ?? 0) }} ج.م
                                </div>
                                <div class="text-sm text-gray-500">إجمالي المعلق</div>
                            </div>
                            <a href="{{ route('admin.payments.pending') }}" 
                               class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors">
                                <i class="fas fa-eye ml-1"></i>
                                التحقق من المدفوعات
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Pending Payments -->
            <div class="bg-white shadow-xl rounded-lg overflow-hidden">
                <div class="px-6 py-4 bg-gradient-to-r from-yellow-500 to-orange-600">
                    <h3 class="text-lg font-medium text-white flex items-center">
                        <i class="fas fa-exclamation-triangle ml-2"></i>
                        مدفوعات تحتاج تحقق
                    </h3>
                </div>
                <div class="divide-y divide-gray-200 max-h-64 overflow-y-auto">
                    @forelse($recentPendingPayments ?? [] as $payment)
                        <div class="px-6 py-4 hover:bg-gray-50 transition-colors">
                            <div class="flex items-center justify-between">
                                <div class="flex-1">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $payment->user->name ?? 'غير معروف' }}
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        {{ number_format($payment->amount) }} ج.م - {{ $payment->created_at->diffForHumans() }}
                                    </div>
                                    @if($payment->subscriptionRequest)
                                        <div class="text-xs text-blue-600">
                                            {{ $payment->subscriptionRequest->subscription_name }}
                                        </div>
                                    @endif
                                </div>
                                <div class="flex items-center space-x-2 space-x-reverse">
                                    <button onclick="quickVerify({{ $payment->id }})" 
                                            class="bg-green-500 hover:bg-green-600 text-white px-2 py-1 rounded text-xs transition-colors">
                                        <i class="fas fa-check"></i>
                                    </button>
                                    <button onclick="viewPayment({{ $payment->id }})" 
                                            class="bg-blue-500 hover:bg-blue-600 text-white px-2 py-1 rounded text-xs transition-colors">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="px-6 py-8 text-center">
                            <div class="text-gray-400 text-4xl mb-2">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <p class="text-gray-500 text-sm">جميع المدفوعات محققة</p>
                        </div>
                    @endforelse
                </div>
                @if(count($recentPendingPayments ?? []) > 0)
                    <div class="bg-gray-50 px-6 py-3 text-center">
                        <a href="{{ route('admin.payments.pending') }}" 
                           class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                            عرض جميع المدفوعات المعلقة ({{ $paymentStats['pending_payments'] ?? 0 }})
                        </a>
                    </div>
                @endif
            </div>
        </div>

        <!-- Recent Activities -->
        <div class="bg-white shadow-xl overflow-hidden sm:rounded-lg">
            <div class="px-6 py-5 border-b border-gray-200">
                <h3 class="text-lg leading-6 font-medium text-gray-900">
                    <i class="fas fa-clipboard-list ml-1"></i> النشاطات الحديثة
                </h3>
                <p class="mt-1 max-w-2xl text-sm text-gray-500">
                    آخر الطلبات والأنشطة في النظام
                </p>
            </div>
            <ul class="divide-y divide-gray-200">
                @forelse($recentRequests ?? [] as $request)
                    <li>
                        <div class="px-6 py-4 hover:bg-gray-50 transition-colors">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <div class="h-10 w-10 rounded-full bg-gradient-to-r from-blue-500 to-purple-500 flex items-center justify-center">
                                            <span class="text-sm font-medium text-white">
                                                {{ substr($request->user->name ?? 'غير معروف', 0, 2) }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="mr-4">
                                        <div class="text-sm font-medium text-gray-900">
                                            طلب اشتراك جديد من {{ $request->user->name ?? 'غير معروف' }}
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            {{ $request->subscription_type ?? 'غير محدد' }} - {{ $request->created_at->diffForHumans() }}
                                        </div>
                                    </div>
                                </div>
                                <div class="flex items-center">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                        @if($request->status === 'pending') bg-yellow-100 text-yellow-800
                                        @elseif($request->status === 'quoted') bg-blue-100 text-blue-800
                                        @elseif($request->status === 'paid') bg-green-100 text-green-800
                                        @else bg-gray-100 text-gray-800 @endif">
                                        @if($request->status === 'pending') معلق
                                        @elseif($request->status === 'quoted') تم التسعير
                                        @elseif($request->status === 'paid') مدفوع
                                        @else {{ $request->status }} @endif
                                    </span>
                                </div>
                            </div>
                        </div>
                    </li>
                @empty
                    <li>
                        <div class="px-6 py-8 text-center">
                            <div class="text-gray-400 text-6xl mb-4">
                                <i class="fas fa-clipboard-list"></i>
                            </div>
                            <p class="text-gray-500">لا توجد نشاطات حديثة</p>
                        </div>
                    </li>
                @endforelse
            </ul>
            @if(count($recentRequests ?? []) > 0)
                <div class="bg-gray-50 px-6 py-3">
                    <div class="text-center">
                        <button class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                            عرض جميع النشاطات
                        </button>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Payment Management Scripts -->
    <script>
        // Setup CSRF token for all AJAX requests
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        function quickVerify(paymentId) {
            if (confirm('هل أنت متأكد من تأكيد هذه الدفعة بشكل سريع؟')) {
                $.ajax({
                    url: `/admin/payments/${paymentId}/verify`,
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        _token: '{{ csrf_token() }}',
                        admin_notes: 'تم التحقق السريع من لوحة البيانات'
                    },
                    success: function(data) {
                        if (data.success) {
                            alert('تم تأكيد الدفعة بنجاح');
                            location.reload();
                        } else {
                            alert(data.message || 'حدث خطأ في تأكيد الدفعة');
                        }
                    },
                    error: function(xhr, status, error) {
                        if (xhr.status === 419) {
                            alert('انتهت صلاحية الجلسة. يرجى إعادة تحميل الصفحة.');
                        } else {
                            alert('حدث خطأ في الاتصال: ' + error);
                        }
                    }
                });
            }
        }

        function viewPayment(paymentId) {
            window.open(`/admin/payments/pending`, '_blank');
        }

        // Auto-refresh statistics every 30 seconds
        setInterval(() => {
            fetch('/admin/dashboard')
                .then(() => {
                    // Update timestamp in the corner
                    const now = new Date();
                    const time = now.toLocaleTimeString('ar-EG');
                    
                    // Add a subtle indicator that data was refreshed
                    const indicators = document.querySelectorAll('.fas.fa-sync-alt');
                    indicators.forEach(indicator => {
                        indicator.style.color = '#10B981';
                        setTimeout(() => {
                            indicator.style.color = '';
                        }, 1000);
                    });
                })
                .catch(() => {
                    console.log('Auto-refresh failed');
                });
        }, 30000);

        // Notification sound for new pending payments (optional)
        let lastPendingCount = {{ $paymentStats['pending_payments'] ?? 0 }};
        
        function checkNewPayments() {
            fetch('/admin/dashboard')
                .then(response => response.text())
                .then(html => {
                    // This is a simplified check - in a real app you'd use an API endpoint
                    const currentCount = {{ $paymentStats['pending_payments'] ?? 0 }};
                    if (currentCount > lastPendingCount) {
                        // New payment notification
                        showNotification(`مدفوعة جديدة بانتظار التحقق (${currentCount})`);
                        lastPendingCount = currentCount;
                    }
                })
                .catch(() => {
                    console.log('Payment check failed');
                });
        }

        function showNotification(message) {
            // Create a simple toast notification
            const notification = document.createElement('div');
            notification.className = 'fixed top-4 left-4 bg-blue-600 text-white px-6 py-3 rounded-lg shadow-lg z-50 transform translate-x-full transition-transform';
            notification.innerHTML = `
                <div class="flex items-center">
                    <i class="fas fa-bell ml-2"></i>
                    ${message}
                </div>
            `;
            document.body.appendChild(notification);
            
            setTimeout(() => {
                notification.classList.remove('translate-x-full');
            }, 100);
            
            setTimeout(() => {
                notification.classList.add('translate-x-full');
                setTimeout(() => {
                    document.body.removeChild(notification);
                }, 300);
            }, 5000);
        }

        // Check for new payments every 60 seconds
        setInterval(checkNewPayments, 60000);
    </script>
</body>
</html>