<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>إدارة الاشتراكات - نظام إدارة الاشتراكات</title>
    
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
                        <h1 class="text-xl font-bold text-blue-600">إدارة الاشتراكات</h1>
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
        <!-- Breadcrumb -->
        <x-breadcrumb :items="[
            ['label' => 'إدارة الاشتراكات', 'url' => '']
        ]" />

        <!-- Header -->
        <div class="md:flex md:items-center md:justify-between mb-6">
            <div class="flex-1 min-w-0">
                <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                    إدارة الاشتراكات
                </h2>
                <p class="mt-1 text-sm text-gray-500">
                    عرض وإدارة جميع اشتراكاتك النشطة وطلبات الاشتراك الجديدة
                </p>
            </div>
            <div class="mt-4 flex md:mt-0 md:ml-4">
                <a href="{{ route('client.subscriptions.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <svg class="ml-2 -mr-1 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    طلب اشتراك جديد
                </a>
            </div>
        </div>

        @if($subscriptionRequests->isNotEmpty())
        <!-- Pending Subscription Requests -->
        <div class="mb-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">
                طلبات الاشتراك الحالية
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($subscriptionRequests as $request)
                <div class="bg-white overflow-hidden shadow rounded-lg border-l-4 
                    @if($request->status == 'pending') border-yellow-400
                    @elseif($request->status == 'quoted') border-blue-400
                    @elseif($request->status == 'paid') border-green-400
                    @endif">
                    <div class="px-4 py-5 sm:p-6">
                        <div class="flex items-center justify-between mb-3">
                            <h4 class="text-lg font-medium text-gray-900">{{ $request->subscription_name }}</h4>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                @if($request->status == 'pending') bg-yellow-100 text-yellow-800
                                @elseif($request->status == 'quoted') bg-blue-100 text-blue-800
                                @elseif($request->status == 'paid') bg-green-100 text-green-800
                                @endif">
                                {{ $request->status_label }}
                            </span>
                        </div>
                        
                        <dl class="grid grid-cols-1 gap-x-4 gap-y-2 sm:grid-cols-2">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">عدد الأجهزة</dt>
                                <dd class="text-sm text-gray-900">{{ $request->device_count }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">تاريخ البداية</dt>
                                <dd class="text-sm text-gray-900">{{ $request->proposed_start_date->format('Y-m-d') }}</dd>
                            </div>
                            @if($request->quoted_price)
                            <div class="sm:col-span-2">
                                <dt class="text-sm font-medium text-gray-500">السعر المقترح</dt>
                                <dd class="text-sm font-semibold text-green-600">{{ number_format($request->quoted_price, 2) }} ج.م</dd>
                            </div>
                            @endif
                        </dl>

                        <div class="mt-4 flex justify-between">
                            <a href="{{ route('client.subscription-requests.show', $request->id) }}" 
                               class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                عرض التفاصيل
                            </a>
                            @if($request->status == 'quoted')
                                <a href="{{ route('client.subscription-requests.payment', $request->id) }}" 
                                   class="text-green-600 hover:text-green-800 text-sm font-medium">
                                    الدفع الآن
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        @if($activeSubscriptions->isNotEmpty())
        <!-- Active Subscriptions -->
          <div class="mb-6">
              <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">
                  الاشتراكات النشطة
              </h3>

          <!-- Subscription Statistics -->
          <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
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
                                  الاشتراكات النشطة
                              </dt>
                              <dd class="mt-1 text-2xl font-semibold text-gray-900">
                                  {{ $activeSubscriptions->where('status', 'active')->count() }}
                              </dd>
                          </div>
                      </div>
                  </div>
              </div>

              <div class="bg-white overflow-hidden shadow rounded-lg">
                  <div class="p-5">
                      <div class="flex items-center">
                          <div class="flex-shrink-0">
                              <div class="w-8 h-8 bg-blue-500 rounded-md flex items-center justify-center">
                                  <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                  </svg>
                              </div>
                          </div>
                          <div class="mr-4 flex-1">
                              <dt class="text-sm font-medium text-gray-500 truncate">
                                  إجمالي المدفوعات الشهرية
                              </dt>
                              <dd class="mt-1 text-2xl font-semibold text-gray-900">
                                  {{ $activeSubscriptions->sum('price') }} ج.م
                              </dd>
                          </div>
                      </div>
                  </div>
              </div>

              <div class="bg-white overflow-hidden shadow rounded-lg">
                  <div class="p-5">
                      <div class="flex items-center">
                          <div class="flex-shrink-0">
                              <div class="w-8 h-8 bg-yellow-500 rounded-md flex items-center justify-center">
                                  <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                  </svg>
                              </div>
                          </div>
                          <div class="mr-4 flex-1">
                              <dt class="text-sm font-medium text-gray-500 truncate">
                                  أيام متبقية
                              </dt>
                              <dd class="mt-1 text-2xl font-semibold text-gray-900">
                                  25
                              </dd>
                          </div>
                      </div>
                  </div>
              </div>
          </div>

          <!-- Subscriptions Table -->
          <div class="bg-white shadow overflow-hidden sm:rounded-md">
              <div class="px-4 py-5 sm:px-6">
                  <h3 class="text-lg leading-6 font-medium text-gray-900">
                      سجل الاشتراكات
                  </h3>
                  <p class="mt-1 max-w-2xl text-sm text-gray-500">
                      عرض تفصيلي لجميع اشتراكاتك
                  </p>
              </div>

              <div class="overflow-x-auto">
                  <table class="min-w-full divide-y divide-gray-200">
                      <thead class="bg-gray-50">
                          <tr>
                              <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                  الباقة
                              </th>
                              <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                  الحالة
                              </th>
                              <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                  تاريخ البداية
                              </th>
                              <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                  تاريخ الانتهاء
                              </th>
                              <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                  السعر
                              </th>
                              <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                  الإجراءات
                              </th>
                          </tr>
                      </thead>
                      <tbody class="bg-white divide-y divide-gray-200">
                          @foreach($activeSubscriptions as $subscription)
                          <tr class="hover:bg-gray-50">
                              <td class="px-6 py-4 whitespace-nowrap">
                                  <div class="flex items-center">
                                      <div class="flex-shrink-0 h-10 w-10">
                                          <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                                              <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                              </svg>
                                          </div>
                                      </div>
                                      <div class="mr-4">
                                          <div class="text-sm font-medium text-gray-900">
                                              {{ $subscription->name }}
                                          </div>
                                          <div class="text-sm text-gray-500">
                                              {{ $subscription->description }}
                                          </div>
                                      </div>
                                  </div>
                              </td>
                              <td class="px-6 py-4 whitespace-nowrap">
                                  <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                      @if($subscription->status == 'active') bg-green-100 text-green-800 
                                      @elseif($subscription->status == 'expired') bg-red-100 text-red-800 
                                      @else bg-yellow-100 text-yellow-800 @endif">
                                      @if($subscription->status == 'active') نشط
                                      @elseif($subscription->status == 'expired') منتهي
                                      @elseif($subscription->status == 'pending') في انتظار التفعيل
                                      @else {{ $subscription->status }} @endif
                                  </span>
                              </td>
                              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                  {{ $subscription->start_date->format('Y-m-d') }}
                              </td>
                              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                  {{ $subscription->end_date->format('Y-m-d') }}
                              </td>
                              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                  {{ $subscription->price }} ج.م
                              </td>
                              <td class="px-6 py-4 whitespace-nowrap text-left text-sm font-medium">
                                  @if($subscription->status == 'active')
                                      <a href="{{ route('client.subscriptions.show', $subscription->id) }}" class="text-blue-600 hover:text-blue-900 ml-3">عرض</a>
                                      <a href="{{ route('client.subscriptions.devices', $subscription->id) }}" class="text-green-600 hover:text-green-900 ml-3">إدارة الأجهزة</a>
                                  @else
                                      <a href="{{ route('client.subscriptions.show', $subscription->id) }}" class="text-gray-600 hover:text-gray-900">عرض</a>
                                  @endif
                              </td>
                          </tr>
                          @endforeach
                      </tbody>
                  </table>
              </div>
          </div>

          <!-- Usage Details -->
          <div class="mt-6 bg-white shadow overflow-hidden sm:rounded-md">
              <div class="px-4 py-5 sm:px-6">
                  <h3 class="text-lg leading-6 font-medium text-gray-900">
                      تفاصيل الاستخدام
                  </h3>
                  <p class="mt-1 max-w-2xl text-sm text-gray-500">
                      معلومات مفصلة عن استخدامك للباقة الحالية
                  </p>
              </div>
              <div class="border-t border-gray-200">
                  <dl>
                      <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                          <dt class="text-sm font-medium text-gray-500">
                              استهلاك البيانات
                          </dt>
                          <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                              <div class="flex items-center">
                                  <div class="flex-1">
                                      <div class="flex justify-between text-sm text-gray-600 mb-1">
                                          <span>45 جيجابايت من أصل 100 جيجابايت</span>
                                          <span>45%</span>
                                      </div>
                                      <div class="w-full bg-gray-200 rounded-full h-2">
                                          <div class="bg-blue-600 h-2 rounded-full" style="width: 45%"></div>
                                      </div>
                                  </div>
                              </div>
                          </dd>
                      </div>
                      <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                          <dt class="text-sm font-medium text-gray-500">
                              عدد الأجهزة المتصلة
                          </dt>
                          <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                              3 من أصل 5 أجهزة
                          </dd>
                      </div>
                      <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                          <dt class="text-sm font-medium text-gray-500">
                              السرعة القصوى
                          </dt>
                          <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                              100 ميجابايت/ثانية
                          </dd>
                      </div>
                      <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                          <dt class="text-sm font-medium text-gray-500">
                              وقت التشغيل هذا الشهر
                          </dt>
                          <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                              99.8% (ممتاز)
                          </dd>
                      </div>
                  </dl>
              </div>
          </div>
        </div>
        @endif
    </div>
</body>
</html>