<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>تفاصيل العميل - {{ $client->name }}</title>
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    
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
                        <a href="{{ route('admin.clients.index') }}" class="text-xl font-bold text-blue-600">
                            <i class="fas fa-arrow-left ml-2"></i>
                            تفاصيل العميل: {{ $client->name }}
                        </a>
                    </div>
                </div>
                
                <div class="flex items-center space-x-4 space-x-reverse">
                    <span class="text-gray-700">{{ auth()->user()->name }}</span>
                    <span class="inline-block bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded-full">مدير</span>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <!-- Breadcrumb -->
        <nav class="flex mb-6" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3 space-x-reverse">
                <li class="inline-flex items-center">
                    <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600">
                        <i class="fas fa-home ml-1"></i>
                        الرئيسية
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <i class="fas fa-chevron-left text-gray-400 mx-1"></i>
                        <a href="{{ route('admin.clients.index') }}" class="text-sm font-medium text-gray-700 hover:text-blue-600">إدارة العملاء</a>
                    </div>
                </li>
                <li>
                    <div class="flex items-center">
                        <i class="fas fa-chevron-left text-gray-400 mx-1"></i>
                        <span class="text-sm font-medium text-gray-500">{{ $client->name }}</span>
                    </div>
                </li>
            </ol>
        </nav>

        @if(session('success'))
            <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                <i class="fas fa-check-circle ml-1"></i>
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                <i class="fas fa-exclamation-circle ml-1"></i>
                {{ session('error') }}
            </div>
        @endif

        <!-- Client Header -->
        <div class="bg-white shadow-xl rounded-lg mb-6">
            <div class="px-6 py-8">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="h-20 w-20 rounded-full bg-gradient-to-r from-blue-500 to-green-500 flex items-center justify-center">
                            <span class="text-2xl font-bold text-white">
                                {{ substr($client->name, 0, 2) }}
                            </span>
                        </div>
                    </div>
                    <div class="mr-6 flex-1">
                        <h1 class="text-3xl font-bold text-gray-900">{{ $client->name }}</h1>
                        <p class="text-lg text-gray-600">{{ $client->email }}</p>
                        <div class="mt-2 flex items-center space-x-4 space-x-reverse">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium 
                                @if($client->clientProfile?->subscription_status === 'active') bg-green-100 text-green-800
                                @elseif($client->clientProfile?->subscription_status === 'trial') bg-yellow-100 text-yellow-800
                                @elseif($client->clientProfile?->subscription_status === 'expired') bg-red-100 text-red-800
                                @else bg-gray-100 text-gray-800 @endif">
                                <i class="fas fa-circle text-xs ml-1"></i>
                                {{ $client->clientProfile?->getSubscriptionStatusText() ?? 'غير محدد' }}
                            </span>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium 
                                @if($client->clientProfile?->subscription_type === 'basic') bg-gray-100 text-gray-800
                                @elseif($client->clientProfile?->subscription_type === 'premium') bg-blue-100 text-blue-800
                                @elseif($client->clientProfile?->subscription_type === 'enterprise') bg-purple-100 text-purple-800
                                @else bg-gray-100 text-gray-800 @endif">
                                <i class="fas fa-tag ml-1"></i>
                                {{ $client->clientProfile?->getSubscriptionTypeText() ?? 'غير محدد' }}
                            </span>
                        </div>
                    </div>
                    <div class="flex space-x-2 space-x-reverse">
                        <a href="{{ route('admin.clients.edit', $client) }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded transition-colors">
                            <i class="fas fa-edit ml-1"></i>
                            تعديل
                        </a>
                        <button onclick="deleteClient({{ $client->id }})" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded transition-colors">
                            <i class="fas fa-trash ml-1"></i>
                            حذف
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <!-- Days Left -->
            <div class="bg-white overflow-hidden shadow-lg rounded-lg border-t-4 border-blue-500">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-calendar-alt text-blue-600"></i>
                            </div>
                        </div>
                        <div class="mr-4 flex-1">
                            <dt class="text-sm font-medium text-gray-500">أيام متبقية</dt>
                            <dd class="mt-1 text-3xl font-bold text-gray-900">
                                @if($stats['subscription_days_left'] !== null)
                                    {{ $stats['subscription_days_left'] > 0 ? $stats['subscription_days_left'] : 0 }}
                                @else
                                    --
                                @endif
                            </dd>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Available Devices -->
            <div class="bg-white overflow-hidden shadow-lg rounded-lg border-t-4 border-green-500">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-mobile-alt text-green-600"></i>
                            </div>
                        </div>
                        <div class="mr-4 flex-1">
                            <dt class="text-sm font-medium text-gray-500">أجهزة متاحة</dt>
                            <dd class="mt-1 text-3xl font-bold text-gray-900">{{ $stats['devices_available'] }}</dd>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Payments -->
            <div class="bg-white overflow-hidden shadow-lg rounded-lg border-t-4 border-purple-500">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-credit-card text-purple-600"></i>
                            </div>
                        </div>
                        <div class="mr-4 flex-1">
                            <dt class="text-sm font-medium text-gray-500">إجمالي المدفوعات</dt>
                            <dd class="mt-1 text-3xl font-bold text-gray-900">{{ number_format($stats['total_payments']) }} جنيه</dd>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Last Payment -->
            <div class="bg-white overflow-hidden shadow-lg rounded-lg border-t-4 border-orange-500">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-clock text-orange-600"></i>
                            </div>
                        </div>
                        <div class="mr-4 flex-1">
                            <dt class="text-sm font-medium text-gray-500">آخر دفعة</dt>
                            <dd class="mt-1 text-lg font-bold text-gray-900">
                                @if($stats['last_payment'])
                                    {{ $stats['last_payment']->diffForHumans() }}
                                @else
                                    لا يوجد
                                @endif
                            </dd>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Client Details Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Basic Information -->
            <div class="bg-white shadow-lg rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">
                        <i class="fas fa-user ml-1"></i>
                        المعلومات الأساسية
                    </h3>
                </div>

                <div class="px-6 py-4">
                  <div class="space-y-6">
                    <!-- Row 1 -->
                    
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">الاسم الكامل</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $client->name }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">البريد الإلكتروني</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $client->email }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">رقم الهاتف</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $client->phone ?? 'غير محدد' }}</dd>
                            </div>
                        </div>

                        <!-- Row 2 -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">العنوان</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $client->address ?? 'غير محدد' }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">تاريخ التسجيل</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $client->created_at->format('Y-m-d H:i') }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">آخر تسجيل دخول</dt>
                                <dd class="mt-1 text-sm text-gray-900">
                                    @if($client->last_login_at)
                                        {{ $client->last_login_at->format('Y-m-d H:i') }}
                                    @else
                                        لم يسجل دخول بعد
                                    @endif
                                </dd>
                            </div>
                        </div>
                    </div>

                    <!-- Client Actions -->
                    <div class="mt-6 pt-4 border-t border-gray-100">
                        <div class="flex space-x-3 space-x-reverse">
                            <button onclick="editClient()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors">
                                <i class="fas fa-edit ml-1"></i>
                                تعديل العميل
                            </button>
                            <button onclick="toggleClientStatus()" class="bg-orange-600 hover:bg-orange-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors">
                                <i class="fas fa-power-off ml-1"></i>
                                {{ $client->status === 'active' ? 'إيقاف' : 'تفعيل' }} العميل
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Active Subscriptions -->
            <div class="bg-white shadow-lg rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">
                        <i class="fas fa-clipboard-list ml-1"></i>
                        الاشتراكات تحت الطلب
                    </h3>
                </div>
                <div class="px-6 py-4">
                    @forelse($client->subscriptionRequests as $ssReq)
                        <!-- Subscription Card -->

                    @empty
                      <p class="text-center text-gray-500 py-8">
                          <i class="fas fa-inbox text-4xl mb-4"></i>
                          لا توجد اشتراكات تحت الطلب حالياً.
                      </p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Active Subscriptions Section -->
        <div class="mt-8 bg-white shadow-lg rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">
                    <i class="fas fa-check-circle ml-1"></i>
                    الاشتراكات الفعلية
                </h3>
                <p class="text-sm text-gray-500 mt-1">الاشتراكات المفعلة حالياً</p>
            </div>
            <div class="px-6 py-4">
                @forelse($activeSubscriptions as $subscription)
                    <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                        <!-- Subscription Header -->
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <h4 class="text-lg font-medium text-gray-900">{{ $subscription->getSubscriptionTypeText() }}</h4>
                                <p class="text-sm text-gray-500">اشتراك رقم #{{ $subscription->id }}</p>
                            </div>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                @if($subscription->subscription_status === 'active') bg-green-100 text-green-800
                                @elseif($subscription->subscription_status === 'trial') bg-yellow-100 text-yellow-800
                                @elseif($subscription->subscription_status === 'expired') bg-red-100 text-red-800
                                @else bg-gray-100 text-gray-800 @endif">
                                {{ $subscription->getSubscriptionStatusText() }}
                            </span>
                        </div>

                        <!-- Subscription Details Grid -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                            <div>
                                <dt class="text-xs font-medium text-gray-500 uppercase tracking-wider">تاريخ البداية</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $subscription->subscription_start_date?->format('Y-m-d') ?? 'غير محدد' }}</dd>
                            </div>
                            <div>
                                <dt class="text-xs font-medium text-gray-500 uppercase tracking-wider">تاريخ الانتهاء</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $subscription->subscription_end_date?->format('Y-m-d') ?? 'غير محدد' }}</dd>
                            </div>
                            <div>
                                <dt class="text-xs font-medium text-gray-500 uppercase tracking-wider">حالة الدفع</dt>
                                <dd class="mt-1">
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium 
                                        @if($subscription->payment_status === 'paid') bg-green-100 text-green-800
                                        @elseif($subscription->payment_status === 'pending') bg-yellow-100 text-yellow-800
                                        @elseif($subscription->payment_status === 'overdue') bg-red-100 text-red-800
                                        @else bg-gray-100 text-gray-800 @endif">
                                        {{ $subscription->getPaymentStatusText() }}
                                    </span>
                                </dd>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                            <div>
                                <dt class="text-xs font-medium text-gray-500 uppercase tracking-wider">عدد الأجهزة</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $subscription->devices_count }} / {{ $subscription->device_limit }}</dd>
                            </div>
                            <div>
                                <dt class="text-xs font-medium text-gray-500 uppercase tracking-wider">دورة الفوترة</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $subscription->getBillingCycleText() }}</dd>
                            </div>
                            <div>
                                <dt class="text-xs font-medium text-gray-500 uppercase tracking-wider">الأيام المتبقية</dt>
                                <dd class="mt-1 text-sm">
                                    @php $remaining = $subscription->getRemainingDays(); @endphp
                                    @if($remaining !== null)
                                        @if($remaining > 0)
                                            <span class="text-green-600 font-bold">{{ $remaining }} يوم</span>
                                        @elseif($remaining == 0)
                                            <span class="text-orange-600 font-bold">ينتهي اليوم</span>
                                        @else
                                            <span class="text-red-600 font-bold">انتهى منذ {{ abs($remaining) }} يوم</span>
                                        @endif
                                    @else
                                        <span class="text-gray-500">غير محدد</span>
                                    @endif
                                </dd>
                            </div>
                        </div>

                        <!-- Subscription Actions -->
                        <div class="flex justify-end space-x-2 space-x-reverse pt-3 border-t border-gray-100">
                            <button onclick="manageSubscriptionDevices({{ $subscription->id }})" class="text-green-600 hover:text-green-800 text-sm font-medium">
                                <i class="fab fa-apple ml-1"></i>
                                إدارة الأجهزة
                            </button>
                            <button onclick="toggleActiveSubscription({{ $subscription->id }})" class="text-orange-600 hover:text-orange-800 text-sm font-medium">
                                <i class="fas fa-power-off ml-1"></i>
                                {{ $subscription->subscription_status === 'active' ? 'إيقاف' : 'تفعيل' }}
                            </button>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8">
                        <i class="fas fa-exclamation-triangle text-yellow-400 text-4xl mb-4"></i>
                        <h4 class="text-lg font-medium text-gray-900 mb-2">لا توجد اشتراكات فعلية</h4>
                        <p class="text-gray-500 mb-4">هذا العميل لا يملك أي اشتراكات مفعلة حتى الآن</p>
                        <p class="text-sm text-gray-400">يمكن تفعيل اشتراك من قسم "الطلبات تحت المعالجة"</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Subscription Requests Section -->
        <div class="mt-8 bg-white shadow-lg rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">
                    <i class="fas fa-clock ml-1"></i>
                    طلبات الاشتراك تحت المعالجة
                </h3>
                <p class="text-sm text-gray-500 mt-1">الطلبات المُرسلة من العميل والتي تحتاج إجراء</p>
            </div>
            <div class="px-6 py-4">
                @forelse($subscriptionRequests as $request)
                    <div class="border border-gray-200 rounded-lg p-4 mb-4 hover:shadow-md transition-shadow">
                        <!-- Request Header -->
                        <div class="flex justify-between items-start mb-3">
                            <div>
                                <h4 class="text-lg font-medium text-gray-900">طلب {{ $request->subscription_type }}</h4>
                                <p class="text-sm text-gray-500">طلب رقم #{{ $request->id }} - {{ $request->created_at->diffForHumans() }}</p>
                            </div>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                @if($request->status === 'pending') bg-yellow-100 text-yellow-800
                                @elseif($request->status === 'quoted') bg-blue-100 text-blue-800
                                @elseif($request->status === 'paid') bg-green-100 text-green-800
                                @elseif($request->status === 'approved') bg-green-100 text-green-800
                                @else bg-red-100 text-red-800 @endif">
                                @if($request->status === 'pending') في الانتظار
                                @elseif($request->status === 'quoted') تم إرسال عرض سعر
                                @elseif($request->status === 'paid') تم الدفع
                                @elseif($request->status === 'approved') تمت الموافقة
                                @else مرفوض @endif
                            </span>
                        </div>

                        <!-- Request Details -->
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-3">
                            <div>
                                <dt class="text-xs font-medium text-gray-500 uppercase tracking-wider">نوع الاشتراك</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ ucfirst($request->subscription_type) }}</dd>
                            </div>
                            <div>
                                <dt class="text-xs font-medium text-gray-500 uppercase tracking-wider">عدد الأجهزة المطلوب</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $request->device_count ?? 'غير محدد' }}</dd>
                            </div>
                            <div>
                                <dt class="text-xs font-medium text-gray-500 uppercase tracking-wider">المبلغ المحدد</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $request->quoted_amount ? number_format($request->quoted_amount) . ' جنيه' : 'لم يُحدد بعد' }}</dd>
                            </div>
                            <div>
                                <dt class="text-xs font-medium text-gray-500 uppercase tracking-wider">طريقة الدفع المفضلة</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $request->preferred_payment_method ?? 'غير محدد' }}</dd>
                            </div>
                        </div>

                        @if($request->description)
                            <div class="mb-3">
                                <dt class="text-xs font-medium text-gray-500 uppercase tracking-wider">تفاصيل الطلب</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $request->description }}</dd>
                            </div>
                        @endif

                        <!-- Request Actions -->
                        <div class="flex justify-end space-x-2 space-x-reverse pt-3 border-t border-gray-100">
                            @if($request->status === 'pending')
                                <button onclick="sendQuote({{ $request->id }})" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                    <i class="fas fa-file-contract ml-1"></i>
                                    إرسال عرض سعر
                                </button>
                                <button onclick="approveDirectly({{ $request->id }})" class="text-green-600 hover:text-green-800 text-sm font-medium">
                                    <i class="fas fa-check ml-1"></i>
                                    موافقة مباشرة
                                </button>
                            @elseif($request->status === 'quoted')
                                <span class="text-blue-600 text-sm">في انتظار دفع العميل</span>
                            @elseif($request->status === 'paid')
                                <button onclick="activateSubscription({{ $request->id }})" class="text-green-600 hover:text-green-800 text-sm font-medium">
                                    <i class="fas fa-play ml-1"></i>
                                    تفعيل الاشتراك
                                </button>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8">
                        <i class="fas fa-inbox text-gray-400 text-4xl mb-4"></i>
                        <h4 class="text-lg font-medium text-gray-900 mb-2">لا توجد طلبات معلقة</h4>
                        <p class="text-gray-500">لا يوجد طلبات اشتراك من هذا العميل حالياً</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Devices Section -->
        <div class="mt-8 grid grid-cols-1 gap-8">
            <!-- Active Devices -->
                <div class="px-6 py-4">
                    @if($client->clientProfile)
                        <div class="space-y-4">
                            <!-- Current Subscription -->
                            <div class="border border-gray-200 rounded-lg p-4">
                                <div class="flex justify-between items-start mb-3">
                                    <div>
                                        <h4 class="font-semibold text-gray-900">الاشتراك الحالي</h4>
                                        <p class="text-sm text-gray-500">{{ $client->clientProfile->getSubscriptionTypeText() }}</p>
                                    </div>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                        @if($client->clientProfile->subscription_status === 'active') bg-green-100 text-green-800
                                        @elseif($client->clientProfile->subscription_status === 'trial') bg-yellow-100 text-yellow-800
                                        @elseif($client->clientProfile->subscription_status === 'expired') bg-red-100 text-red-800
                                        @else bg-gray-100 text-gray-800 @endif">
                                        {{ $client->clientProfile->getSubscriptionStatusText() }}
                                    </span>
                                </div>
                                
                                <div class="grid grid-cols-2 gap-4 text-sm">
                                    <div>
                                        <span class="font-medium text-gray-700">تاريخ البداية:</span>
                                        <p class="text-gray-600">{{ $client->clientProfile->subscription_start_date?->format('Y-m-d') ?? 'غير محدد' }}</p>
                                    </div>
                                    <div>
                                        <span class="font-medium text-gray-700">تاريخ الانتهاء:</span>
                                        <p class="text-gray-600">{{ $client->clientProfile->subscription_end_date?->format('Y-m-d') ?? 'غير محدد' }}</p>
                                    </div>
                                    <div>
                                        <span class="font-medium text-gray-700">دورة الفوترة:</span>
                                        <p class="text-gray-600">{{ $client->clientProfile->getBillingCycleText() }}</p>
                                    </div>
                                    <div>
                                        <span class="font-medium text-gray-700">حالة الدفع:</span>
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium 
                                            @if($client->clientProfile->payment_status === 'paid') bg-green-100 text-green-800
                                            @elseif($client->clientProfile->payment_status === 'pending') bg-yellow-100 text-yellow-800
                                            @elseif($client->clientProfile->payment_status === 'overdue') bg-red-100 text-red-800
                                            @else bg-gray-100 text-gray-800 @endif">
                                            {{ $client->clientProfile->getPaymentStatusText() }}
                                        </span>
                                    </div>
                                </div>

                                <div class="mt-4 pt-4 border-t border-gray-100">
                                    <div class="flex justify-between items-center">
                                        <div class="text-sm">
                                            <span class="font-medium text-gray-700">الأيام المتبقية: </span>
                                            @php
                                                $remaining = $client->clientProfile->getRemainingDays();
                                            @endphp
                                            @if($remaining !== null)
                                                @if($remaining > 0)
                                                    <span class="text-green-600 font-bold">{{ $remaining }} يوم</span>
                                                @elseif($remaining == 0)
                                                    <span class="text-orange-600 font-bold">ينتهي اليوم</span>
                                                @else
                                                    <span class="text-red-600 font-bold">انتهى منذ {{ abs($remaining) }} يوم</span>
                                                @endif
                                            @else
                                                <span class="text-gray-500">غير محدد</span>
                                            @endif
                                        </div>
                                        <div class="flex space-x-2 space-x-reverse">
                                            <button onclick="editSubscription()" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                                <i class="fas fa-edit ml-1"></i>
                                                تعديل
                                            </button>
                                            <button onclick="renewSubscription()" class="text-green-600 hover:text-green-800 text-sm font-medium">
                                                <i class="fas fa-refresh ml-1"></i>
                                                تجديد
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-8">
                            <i class="fas fa-calendar-times text-gray-400 text-4xl mb-4"></i>
                            <p class="text-gray-500 mb-4">لا توجد اشتراكات حتى الآن</p>
                            <button onclick="addSubscription()" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded transition-colors">
                                <i class="fas fa-plus ml-1"></i>
                                إنشاء اشتراك جديد
                            </button>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Active Devices -->
            <div class="bg-white shadow-lg rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">
                        <i class="fab fa-apple ml-1"></i>
                        الأجهزة النشطة
                    </h3>
                    <p class="text-sm text-gray-500 mt-1">أجهزة Apple المرتبطة بالاشتراكات</p>
                </div>
                <div class="px-6 py-4">
                    @if($client->clientProfile)
                        <div class="mb-4">
                            <div class="bg-gray-50 rounded-lg p-4">
                                <div class="flex justify-between items-center mb-2">
                                    <span class="text-sm font-medium text-gray-700">استخدام الأجهزة</span>
                                    <span class="text-sm text-gray-600">
                                        {{ $client->clientProfile->devices_count }} / {{ $client->clientProfile->device_limit }}
                                    </span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    @php
                                        $percentage = $client->clientProfile->device_limit > 0 ? 
                                                     ($client->clientProfile->devices_count / $client->clientProfile->device_limit) * 100 : 0;
                                        $colorClass = $percentage >= 90 ? 'bg-red-600' : ($percentage >= 70 ? 'bg-yellow-600' : 'bg-green-600');
                                    @endphp
                                    <div class="{{ $colorClass }} h-2 rounded-full transition-all duration-300" 
                                         style="width: {{ min($percentage, 100) }}%"></div>
                                </div>
                                @if($client->clientProfile->devices_count >= $client->clientProfile->device_limit)
                                    <p class="text-red-600 text-xs mt-2">
                                        <i class="fas fa-exclamation-triangle ml-1"></i>
                                        تم الوصول للحد الأقصى من الأجهزة
                                    </p>
                                @else
                                    <p class="text-green-600 text-xs mt-2">
                                        <i class="fas fa-check-circle ml-1"></i>
                                        يمكن إضافة {{ $client->clientProfile->getAvailableDevices() }} جهاز إضافي
                                    </p>
                                @endif
                            </div>
                        </div>

                        <!-- Apple Devices List -->
                        <div class="space-y-3" id="devices-list">
                            @forelse($client->clientDevices as $device)
                                <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow device-item" data-device-id="{{ $device->id }}">
                                    <div class="flex items-start justify-between">
                                        <div class="flex items-start">
                                            <div class="w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center ml-3 flex-shrink-0">
                                                <i class="{{ $device->getDeviceTypeIcon() }} text-gray-600"></i>
                                            </div>
                                            <div class="flex-1">
                                                <h4 class="text-sm font-medium text-gray-900">{{ $device->device_name }}</h4>
                                                <p class="text-xs text-gray-500 mt-1">{{ $device->getDeviceFullInfo() }}</p>
                                                @if($device->device_serial)
                                                    <p class="text-xs text-gray-400 mt-1">Serial: {{ $device->getFormattedSerial() }}</p>
                                                @endif
                                                <p class="text-xs text-gray-400 mt-1">
                                                    @if($device->activation_date)
                                                        تم التفعيل: {{ $device->activation_date->format('Y-m-d') }}
                                                    @else
                                                        غير مُفعل
                                                    @endif
                                                </p>
                                            </div>
                                        </div>
                                        <div class="flex flex-col items-end space-y-2">
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $device->getStatusColor() }}">
                                                {{ $device->getStatusText() }}
                                            </span>
                                            <div class="flex space-x-1 space-x-reverse">
                                                <button onclick="viewDeviceDetails({{ $device->id }})" 
                                                        class="text-blue-600 hover:text-blue-800 text-sm transition-colors" 
                                                        title="عرض التفاصيل">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                <button onclick="toggleDeviceStatus({{ $device->id }})" 
                                                        class="text-orange-600 hover:text-orange-800 text-sm transition-colors" 
                                                        title="{{ $device->status === 'active' ? 'إيقاف' : 'تفعيل' }}">
                                                    <i class="fas fa-power-off"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-8" id="no-devices-message">
                                    <i class="fab fa-apple text-gray-400 text-4xl mb-3"></i>
                                    <h4 class="text-lg font-medium text-gray-900 mb-2">لا توجد أجهزة مسجلة</h4>
                                    <p class="text-gray-500 mb-2">لم يقم العميل بتسجيل أي أجهزة Apple بعد</p>
                                    <p class="text-sm text-gray-400">الأجهزة يتم إضافتها من تطبيق العميل باستخدام الرقم المسلسل</p>
                                </div>
                            @endforelse
                        </div>
                    @else
                        <div class="text-center py-8">
                            <i class="fas fa-mobile-alt text-gray-400 text-4xl mb-4"></i>
                            <p class="text-gray-500">يجب إنشاء اشتراك أولاً لإدارة الأجهزة</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Notes Section -->
        @if($client->notes || $client->clientProfile?->client_notes)
            <div class="mt-8 bg-white shadow-lg rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">
                        <i class="fas fa-sticky-note ml-1"></i>
                        الملاحظات
                    </h3>
                </div>
                <div class="px-6 py-4">
                    @if($client->notes)
                        <div class="mb-4">
                            <h4 class="font-medium text-gray-700 mb-2">ملاحظات عامة:</h4>
                            <p class="text-gray-600 whitespace-pre-wrap">{{ $client->notes }}</p>
                        </div>
                    @endif
                    @if($client->clientProfile?->client_notes)
                        <div>
                            <h4 class="font-medium text-gray-700 mb-2">ملاحظات الاشتراك:</h4>
                            <p class="text-gray-600 whitespace-pre-wrap">{{ $client->clientProfile->client_notes }}</p>
                        </div>
                    @endif
                </div>
            </div>
        @endif

        <!-- Activities Section -->
        <div class="mt-8 bg-white shadow-lg rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                <h3 class="text-lg font-medium text-gray-900">
                    <i class="fas fa-history ml-1"></i>
                    النشاطات الأخيرة
                </h3>
                <button onclick="loadActivities()" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                    <i class="fas fa-refresh ml-1"></i>
                    تحديث
                </button>
            </div>
            <div id="activities-container" class="px-6 py-4">
                <div class="text-center py-4">
                    <i class="fas fa-spinner fa-spin text-gray-400 text-2xl"></i>
                    <p class="text-gray-500 mt-2">جاري تحميل النشاطات...</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3 text-center">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                    <i class="fas fa-exclamation-triangle text-red-600"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mt-2">تأكيد الحذف</h3>
                <div class="mt-2 px-7 py-3">
                    <p class="text-sm text-gray-500">
                        هل أنت متأكد من حذف هذا العميل؟ سيتم حذف جميع بياناته ولا يمكن التراجع عن هذا الإجراء.
                    </p>
                </div>
                <div class="items-center px-4 py-3">
                    <form id="deleteForm" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="px-4 py-2 bg-red-500 text-white text-base font-medium rounded-md w-24 mr-2 hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-300">
                            حذف
                        </button>
                    </form>
                    <button onclick="closeDeleteModal()" class="px-4 py-2 bg-gray-500 text-white text-base font-medium rounded-md w-24 hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-300">
                        إلغاء
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function deleteClient(clientId) {
            const modal = document.getElementById('deleteModal');
            const form = document.getElementById('deleteForm');
            form.action = `/admin/clients/${clientId}`;
            modal.classList.remove('hidden');
        }

        function closeDeleteModal() {
            const modal = document.getElementById('deleteModal');
            modal.classList.add('hidden');
        }

        function addSubscription() {
            // Redirect to subscription creation or show modal
            window.location.href = "{{ route('admin.clients.edit', $client) }}#subscription";
        }

        function editSubscription() {
            // Redirect to subscription edit
            window.location.href = "{{ route('admin.clients.edit', $client) }}#subscription";
        }

        // Subscription management functions
        function manageSubscription(subscriptionId) {
            window.location.href = `{{ route('admin.clients.edit', $client) }}#subscription`;
        }

        function manageDevices(subscriptionId) {
            // Redirect to devices management for this subscription
            alert('إدارة أجهزة الاشتراك #' + subscriptionId);
        }

        function toggleSubscription(subscriptionId) {
            @if($client->clientProfile)
            const currentStatus = '{{ $client->clientProfile->subscription_status }}';
            const newStatus = currentStatus === 'active' ? 'inactive' : 'active';
            const action = newStatus === 'active' ? 'تفعيل' : 'إيقاف';
            
            if (confirm(`هل تريد ${action} هذا الاشتراك؟`)) {
                fetch(`/admin/clients/{{ $client->id }}/toggle-subscription`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ 
                        subscription_id: subscriptionId,
                        status: newStatus 
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert(data.message || 'حدث خطأ في تحديث حالة الاشتراك');
                    }
                })
                .catch(() => {
                    alert('حدث خطأ في الاتصال');
                });
            }
            @endif
        }

        // New subscription management functions
        function manageSubscriptionDevices(subscriptionId) {
            // Redirect to subscription devices management
            window.location.href = `/admin/clients/{{ $client->id }}/subscription/${subscriptionId}/devices`;
        }

        function toggleActiveSubscription(subscriptionId) {
            @if($client->clientProfile)
            const currentStatus = '{{ $client->clientProfile->subscription_status }}';
            const newStatus = currentStatus === 'active' ? 'inactive' : 'active';
            const action = newStatus === 'active' ? 'تفعيل' : 'إيقاف';
            
            if (confirm(`هل تريد ${action} هذا الاشتراك؟`)) {
                fetch(`/admin/clients/{{ $client->id }}/toggle-subscription`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ 
                        subscription_id: subscriptionId,
                        status: newStatus 
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert(data.message || 'حدث خطأ في تحديث حالة الاشتراك');
                    }
                })
                .catch(() => {
                    alert('حدث خطأ في الاتصال');
                });
            }
            @endif
        }

        function sendQuote(requestId) {
            window.location.href = `/admin/subscription-requests/${requestId}/quote`;
        }

        function approveDirectly(requestId) {
            if (confirm('هل تريد الموافقة على هذا الطلب مباشرة؟ سيتم إنشاء اشتراك فعلي للعميل.')) {
                fetch(`/admin/subscription-requests/${requestId}/approve`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert(data.message || 'حدث خطأ في الموافقة على الطلب');
                    }
                })
                .catch(() => {
                    alert('حدث خطأ في الاتصال');
                });
            }
        }

        function activateSubscription(requestId) {
            if (confirm('هل تريد تفعيل اشتراك هذا العميل؟ سيتم إنشاء اشتراك فعلي.')) {
                fetch(`/admin/subscription-requests/${requestId}/activate`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert(data.message || 'حدث خطأ في تفعيل الاشتراك');
                    }
                })
                .catch(() => {
                    alert('حدث خطأ في الاتصال');
                });
            }
        }

        // New functions for client and device management
        function editClient() {
            document.getElementById('editClientModal').classList.remove('hidden');
        }

        function closeEditClientModal() {
            document.getElementById('editClientModal').classList.add('hidden');
        }

        function updateClient(event) {
            event.preventDefault();
            const form = event.target;
            const formData = new FormData(form);
            const data = Object.fromEntries(formData);

            fetch(`{{ route('admin.clients.update', $client) }}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    ...data,
                    _method: 'PUT'
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert(data.message || 'حدث خطأ في تحديث البيانات');
                }
            })
            .catch(() => {
                alert('حدث خطأ في الاتصال');
            });
        }

        function toggleClientStatus() {
            const currentStatus = '{{ $client->status }}';
            const newStatus = currentStatus === 'active' ? 'inactive' : 'active';
            const action = newStatus === 'active' ? 'تفعيل' : 'إيقاف';
            
            if (confirm(`هل تريد ${action} هذا العميل؟`)) {
                fetch(`{{ route('admin.clients.update', $client) }}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        status: newStatus,
                        _method: 'PUT'
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert(data.message || 'حدث خطأ في تحديث حالة العميل');
                    }
                })
                .catch(() => {
                    alert('حدث خطأ في الاتصال');
                });
            }
        }

        // Device management functions
        function viewDeviceDetails(deviceId) {
            fetch(`/admin/clients/{{ $client->id }}/device-details/${deviceId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const content = `
                            <div class="space-y-4">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="text-sm font-medium text-gray-500">اسم الجهاز</label>
                                        <p class="text-gray-900">${data.device.device_name}</p>
                                    </div>
                                    <div>
                                        <label class="text-sm font-medium text-gray-500">نوع الجهاز</label>
                                        <p class="text-gray-900">${data.device.device_type_text}</p>
                                    </div>
                                    <div>
                                        <label class="text-sm font-medium text-gray-500">الرقم المسلسل</label>
                                        <p class="text-gray-900 font-mono">${data.device.device_serial || 'غير محدد'}</p>
                                    </div>
                                    <div>
                                        <label class="text-sm font-medium text-gray-500">موديل الجهاز</label>
                                        <p class="text-gray-900">${data.device.device_model || 'غير محدد'}</p>
                                    </div>
                                    <div>
                                        <label class="text-sm font-medium text-gray-500">إصدار iOS</label>
                                        <p class="text-gray-900">${data.device.ios_version || 'غير محدد'}</p>
                                    </div>
                                    <div>
                                        <label class="text-sm font-medium text-gray-500">الحالة</label>
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium ${data.device.status_color}">
                                            ${data.device.status_text}
                                        </span>
                                    </div>
                                    <div>
                                        <label class="text-sm font-medium text-gray-500">تاريخ التفعيل</label>
                                        <p class="text-gray-900">${data.device.activation_date || 'غير مُفعل'}</p>
                                    </div>
                                    <div>
                                        <label class="text-sm font-medium text-gray-500">آخر اتصال</label>
                                        <p class="text-gray-900">${data.device.last_connection || 'لم يتصل بعد'}</p>
                                    </div>
                                </div>
                                ${data.device.notes ? `
                                    <div>
                                        <label class="text-sm font-medium text-gray-500">ملاحظات</label>
                                        <p class="text-gray-900 mt-1">${data.device.notes}</p>
                                    </div>
                                ` : ''}
                            </div>
                        `;
                        document.getElementById('deviceDetailsContent').innerHTML = content;
                        document.getElementById('deviceDetailsModal').classList.remove('hidden');
                    } else {
                        alert('حدث خطأ في تحميل بيانات الجهاز');
                    }
                })
                .catch(() => {
                    alert('حدث خطأ في الاتصال');
                });
        }

        function closeDeviceDetailsModal() {
            document.getElementById('deviceDetailsModal').classList.add('hidden');
        }

        function toggleDeviceStatus(deviceId) {
            const deviceElement = document.querySelector(`[data-device-id="${deviceId}"]`);
            const statusElement = deviceElement.querySelector('.inline-flex.items-center');
            const currentStatus = statusElement.textContent.trim() === 'مُفعّل' ? 'active' : 'inactive';
            const newStatus = currentStatus === 'active' ? 'inactive' : 'active';
            const action = newStatus === 'active' ? 'تفعيل' : 'إيقاف';
            
            if (confirm(`هل تريد ${action} هذا الجهاز؟`)) {
                fetch(`/admin/clients/{{ $client->id }}/toggle-device-status/${deviceId}`, {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ status: newStatus })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Update device status in UI
                        statusElement.textContent = data.device.status_text;
                        statusElement.className = `inline-flex items-center px-2 py-1 rounded-full text-xs font-medium ${data.device.status_color}`;
                    } else {
                        alert(data.message || 'حدث خطأ في تحديث حالة الجهاز');
                    }
                })
                .catch(() => {
                    alert('حدث خطأ في الاتصال');
                });
            }
        }

        function updateDeviceStatus(deviceId, status) {
            fetch(`/admin/clients/{{ $client->id }}/update-device-status/${deviceId}`, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ status: status })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update device status in UI
                    const deviceElement = document.querySelector(`[data-device-id="${deviceId}"]`);
                    if (deviceElement) {
                        const selectElement = deviceElement.querySelector('select');
                        selectElement.className = `text-xs border-0 bg-transparent ${data.device.status_color} px-2 py-1 rounded-full font-medium focus:ring-2 focus:ring-blue-500`;
                    }
                } else {
                    alert(data.message || 'حدث خطأ في تحديث حالة الجهاز');
                }
            })
            .catch(() => {
                alert('حدث خطأ في الاتصال');
            });
        }

        function updateDevicesList(data) {
            const devicesList = document.getElementById('devices-list');
            const noDevicesMessage = document.getElementById('no-devices-message');
            
            if (noDevicesMessage) {
                noDevicesMessage.remove();
            }

            const deviceHtml = `
                <div class="flex items-center justify-between p-3 border border-gray-200 rounded-lg device-item" data-device-id="${data.device.id}">
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center ml-3">
                            <i class="fas fa-mobile-alt text-blue-600 text-sm"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">${data.device.name}</p>
                            <p class="text-xs text-gray-500">
                                ${data.device.type} - آخر اتصال: ${data.device.last_connection || 'لم يتصل بعد'}
                            </p>
                        </div>
                    </div>
                    <div class="flex space-x-2 space-x-reverse items-center">
                        <select onchange="updateDeviceStatus(${data.device.id}, this.value)" 
                                class="text-xs border-0 bg-transparent bg-green-100 text-green-800 px-2 py-1 rounded-full font-medium focus:ring-2 focus:ring-blue-500">
                            <option value="active" selected class="bg-white text-gray-900">نشط</option>
                            <option value="inactive" class="bg-white text-gray-900">غير نشط</option>
                            <option value="suspended" class="bg-white text-gray-900">معلق</option>
                        </select>
                        <button onclick="removeDevice(${data.device.id})" 
                                class="text-red-600 hover:text-red-800 text-sm transition-colors" 
                                title="حذف الجهاز">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
            `;
            
            devicesList.insertAdjacentHTML('beforeend', deviceHtml);
        }

        function updateDevicesCount(devicesCount, availableDevices) {
            // Update the devices count display
            const countDisplay = document.querySelector('.text-sm.text-gray-600');
            if (countDisplay) {
                const totalDevices = devicesCount + availableDevices;
                countDisplay.textContent = `${devicesCount} / ${totalDevices}`;
            }

            // Update progress bar
            const progressBar = document.querySelector('.bg-green-600, .bg-yellow-600, .bg-red-600');
            if (progressBar) {
                const totalDevices = devicesCount + availableDevices;
                const percentage = totalDevices > 0 ? (devicesCount / totalDevices) * 100 : 0;
                const colorClass = percentage >= 90 ? 'bg-red-600' : (percentage >= 70 ? 'bg-yellow-600' : 'bg-green-600');
                
                progressBar.className = `${colorClass} h-2 rounded-full transition-all duration-300`;
                progressBar.style.width = `${Math.min(percentage, 100)}%`;
            }
        }

        function loadActivities() {
            const container = document.getElementById('activities-container');
            container.innerHTML = `
                <div class="text-center py-4">
                    <i class="fas fa-spinner fa-spin text-gray-400 text-2xl"></i>
                    <p class="text-gray-500 mt-2">جاري تحميل النشاطات...</p>
                </div>
            `;

            fetch(`/admin/clients/{{ $client->id }}/activities`)
                .then(response => response.json())
                .then(activities => {
                    if (activities.length === 0) {
                        container.innerHTML = `
                            <div class="text-center py-8">
                                <i class="fas fa-history text-gray-400 text-4xl mb-2"></i>
                                <p class="text-gray-500">لا توجد نشاطات مسجلة</p>
                            </div>
                        `;
                        return;
                    }

                    let html = '<ul class="divide-y divide-gray-200">';
                    activities.forEach(activity => {
                        const typeIcons = {
                            'subscription_request': 'fas fa-file-alt',
                            'subscription': 'fas fa-check-circle',
                            'payment': 'fas fa-credit-card',
                            'profile_updated': 'fas fa-edit'
                        };
                        const icon = typeIcons[activity.type] || 'fas fa-circle';
                        
                        html += `
                            <li class="py-3">
                                <div class="flex items-center space-x-3 space-x-reverse">
                                    <div class="flex-shrink-0">
                                        <i class="${icon} text-gray-400"></i>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-900">${activity.description}</p>
                                        <p class="text-sm text-gray-500">${activity.date}</p>
                                        ${activity.details ? `<p class="text-xs text-gray-400 mt-1">${JSON.stringify(activity.details)}</p>` : ''}
                                    </div>
                                    <div class="flex-shrink-0">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                            ${activity.status === 'completed' ? 'bg-green-100 text-green-800' : 
                                              activity.status === 'pending' ? 'bg-yellow-100 text-yellow-800' :
                                              'bg-gray-100 text-gray-800'}">
                                            ${activity.status}
                                        </span>
                                    </div>
                                </div>
                            </li>
                        `;
                    });
                    html += '</ul>';
                    container.innerHTML = html;
                })
                .catch(error => {
                    container.innerHTML = `
                        <div class="text-center py-8">
                            <i class="fas fa-exclamation-triangle text-red-400 text-4xl mb-2"></i>
                            <p class="text-red-500">خطأ في تحميل النشاطات</p>
                        </div>
                    `;
                });
        }

        // Load activities on page load
        document.addEventListener('DOMContentLoaded', function() {
            loadActivities();
        });

        // Close modal when clicking outside
        document.getElementById('deleteModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeDeleteModal();
            }
        });
    </script>

    <!-- Device Details Modal -->
    <div id="deviceDetailsModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-bold text-gray-900">تفاصيل الجهاز</h3>
                    <button onclick="closeDeviceDetailsModal()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                <div id="deviceDetailsContent">
                    <!-- Device details will be loaded here -->
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Client Modal -->
    <div id="editClientModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-10 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-2/3 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-bold text-gray-900">تعديل بيانات العميل</h3>
                    <button onclick="closeEditClientModal()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                <form id="editClientForm" onsubmit="updateClient(event)">
                    @csrf
                    @method('PUT')
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">الاسم الكامل</label>
                            <input type="text" name="name" value="{{ $client->name }}" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">البريد الإلكتروني</label>
                            <input type="email" name="email" value="{{ $client->email }}" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">رقم الهاتف</label>
                            <input type="text" name="phone" value="{{ $client->phone }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">الحالة</label>
                            <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="active" {{ $client->status === 'active' ? 'selected' : '' }}>مُفعّل</option>
                                <option value="inactive" {{ $client->status === 'inactive' ? 'selected' : '' }}>موقوف</option>
                            </select>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">العنوان</label>
                            <input type="text" name="address" value="{{ $client->address }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">ملاحظات</label>
                            <textarea name="notes" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">{{ $client->notes }}</textarea>
                        </div>
                    </div>
                    <div class="flex justify-end space-x-3 space-x-reverse mt-6">
                        <button type="button" onclick="closeEditClientModal()" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 transition-colors">
                            إلغاء
                        </button>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                            <i class="fas fa-save ml-1"></i>
                            حفظ التغييرات
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</body>
</html>