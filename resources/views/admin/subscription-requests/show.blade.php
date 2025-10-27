<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>تفاصيل طلب الاشتراك #{{ $subscriptionRequest->id }} - لوحة تحكم الإدارة</title>
    
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
                        <h1 class="text-xl font-bold text-blue-600">تفاصيل طلب الاشتراك #{{ $subscriptionRequest->id }}</h1>
                    </div>
                </div>
                
                <div class="flex items-center space-x-4 space-x-reverse">
                    <div class="flex space-x-2 space-x-reverse">
                        <a href="{{ route('admin.subscription-requests.index') }}" class="text-gray-600 hover:text-blue-600 px-3 py-2 rounded-md text-sm">
                            طلبات الاشتراك
                        </a>
                        <a href="{{ route('admin.dashboard') }}" class="text-gray-600 hover:text-blue-600 px-3 py-2 rounded-md text-sm">
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

        <!-- Status and Actions Header -->
        <div class="bg-white overflow-hidden shadow rounded-lg mb-6">
            <div class="px-4 py-5 sm:p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">{{ $subscriptionRequest->subscription_name }}</h2>
                        <p class="mt-1 text-sm text-gray-500">
                            طلب رقم #{{ $subscriptionRequest->id }} - تم الإرسال في {{ $subscriptionRequest->created_at->format('Y-m-d H:i') }}
                        </p>
                    </div>
                    <div class="flex items-center space-x-3 space-x-reverse">
                        <span class="inline-flex items-center px-3 py-2 rounded-full text-sm font-medium 
                            @if($subscriptionRequest->status == 'pending') bg-yellow-100 text-yellow-800
                            @elseif($subscriptionRequest->status == 'quoted') bg-blue-100 text-blue-800
                            @elseif($subscriptionRequest->status == 'paid') bg-green-100 text-green-800
                            @elseif($subscriptionRequest->status == 'active') bg-green-100 text-green-800
                            @elseif($subscriptionRequest->status == 'rejected') bg-red-100 text-red-800
                            @endif">
                            {{ $subscriptionRequest->status_label }}
                        </span>

                        @if($subscriptionRequest->status == 'pending')
                        <div class="flex space-x-2 space-x-reverse">
                            <a href="{{ route('admin.subscription-requests.quote', $subscriptionRequest->id) }}" 
                               class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded text-sm font-medium">
                                إرسال عرض سعر
                            </a>
                            <button onclick="showRejectModal()" 
                                    class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded text-sm font-medium">
                                رفض الطلب
                            </button>
                        </div>
                        @endif

                        @if($subscriptionRequest->status == 'paid' && $subscriptionRequest->payments->where('status', 'pending_verification')->count() > 0)
                        <a href="{{ route('admin.payments.pending') }}" 
                           class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded text-sm font-medium">
                            التحقق من الدفع
                        </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            <!-- Client Information -->
            <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                <div class="px-4 py-5 sm:px-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">
                        معلومات العميل
                    </h3>
                </div>
                <div class="border-t border-gray-200">
                    <dl>
                        <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">الاسم</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $subscriptionRequest->user->name }}</dd>
                        </div>
                        <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">البريد الإلكتروني</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $subscriptionRequest->user->email }}</dd>
                        </div>
                        <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">تاريخ التسجيل</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $subscriptionRequest->user->created_at->format('Y-m-d') }}</dd>
                        </div>
                        <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">إجمالي الاشتراكات</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $subscriptionRequest->user->subscriptions()->count() }}</dd>
                        </div>
                    </dl>
                </div>
            </div>

            <!-- Request Details -->
            <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                <div class="px-4 py-5 sm:px-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">
                        تفاصيل الطلب
                    </h3>
                </div>
                <div class="border-t border-gray-200">
                    <dl>
                        <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">اسم الاشتراك</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $subscriptionRequest->subscription_name }}</dd>
                        </div>
                        <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">عدد الأجهزة</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $subscriptionRequest->device_count }} جهاز</dd>
                        </div>
                        <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">تاريخ البداية المقترح</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $subscriptionRequest->proposed_start_date->format('Y-m-d') }}</dd>
                        </div>
                        <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">تاريخ الطلب</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $subscriptionRequest->created_at->format('Y-m-d H:i') }}</dd>
                        </div>
                    </dl>
                </div>
            </div>
        </div>

        @if($subscriptionRequest->notes)
        <!-- Client Notes -->
        <div class="bg-white shadow overflow-hidden sm:rounded-lg mb-6">
            <div class="px-4 py-5 sm:px-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900">
                    ملاحظات العميل
                </h3>
            </div>
            <div class="border-t border-gray-200 px-4 py-5 sm:p-6">
                <p class="text-sm text-gray-900">{{ $subscriptionRequest->notes }}</p>
            </div>
        </div>
        @endif

        @if($subscriptionRequest->status != 'pending')
        <!-- Quote Information -->
        <div class="bg-white shadow overflow-hidden sm:rounded-lg mb-6">
            <div class="px-4 py-5 sm:px-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900">
                    عرض السعر
                </h3>
                @if($subscriptionRequest->quoted_at)
                <p class="mt-1 max-w-2xl text-sm text-gray-500">
                    تم إرسال العرض في {{ $subscriptionRequest->quoted_at->format('Y-m-d H:i') }}
                </p>
                @endif
            </div>
            <div class="border-t border-gray-200">
                <dl>
                    @if($subscriptionRequest->quoted_price)
                    <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">السعر المقترح</dt>
                        <dd class="mt-1 text-lg font-semibold text-green-600 sm:mt-0 sm:col-span-2">
                            {{ number_format($subscriptionRequest->quoted_price, 2) }} جنيه مصري/شهر
                        </dd>
                    </div>
                    @endif
                    @if($subscriptionRequest->payment_method)
                    <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">طريقة الدفع</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $subscriptionRequest->payment_method }}</dd>
                    </div>
                    @endif
                    @if($subscriptionRequest->admin_notes)
                    <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">ملاحظات الإدارة</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $subscriptionRequest->admin_notes }}</dd>
                    </div>
                    @endif
                </dl>
            </div>
        </div>
        @endif

        @if($subscriptionRequest->payments->count() > 0)
        <!-- Payment Information -->
        <div class="bg-white shadow overflow-hidden sm:rounded-lg mb-6">
            <div class="px-4 py-5 sm:px-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900">
                    معلومات الدفع
                </h3>
            </div>
            <div class="border-t border-gray-200">
                @foreach($subscriptionRequest->payments as $payment)
                <div class="border-b border-gray-100 last:border-b-0">
                    <dl>
                        <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">حالة الدفع</dt>
                            <dd class="mt-1 text-sm sm:mt-0 sm:col-span-2">
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                    @if($payment->status == 'pending_verification') bg-yellow-100 text-yellow-800
                                    @elseif($payment->status == 'verified') bg-green-100 text-green-800
                                    @elseif($payment->status == 'rejected') bg-red-100 text-red-800
                                    @endif">
                                    @if($payment->status == 'pending_verification') قيد التحقق
                                    @elseif($payment->status == 'verified') تم التحقق
                                    @elseif($payment->status == 'rejected') مرفوض
                                    @endif
                                </span>
                            </dd>
                        </div>
                        <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">المبلغ</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ number_format($payment->amount, 2) }} جنيه</dd>
                        </div>
                        @if($payment->transaction_reference)
                        <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">رقم المرجع</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $payment->transaction_reference }}</dd>
                        </div>
                        @endif
                        <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">تاريخ الدفع</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $payment->paid_at->format('Y-m-d H:i') }}</dd>
                        </div>
                        @if($payment->receipt_file)
                        <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">إيصال الدفع</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                <a href="{{ asset('storage/' . $payment->receipt_file) }}" 
                                   target="_blank"
                                   class="text-blue-600 hover:text-blue-800 underline">
                                    عرض الإيصال
                                </a>
                            </dd>
                        </div>
                        @endif
                        @if($payment->notes)
                        <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">ملاحظات الدفع</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $payment->notes }}</dd>
                        </div>
                        @endif
                    </dl>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        @if($subscriptionRequest->subscription)
        <!-- Active Subscription -->
        <div class="bg-white shadow overflow-hidden sm:rounded-lg mb-6">
            <div class="px-4 py-5 sm:px-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900">
                    الاشتراك النشط
                </h3>
            </div>
            <div class="border-t border-gray-200">
                <dl>
                    <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">رقم الاشتراك</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">#{{ $subscriptionRequest->subscription->id }}</dd>
                    </div>
                    <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">تاريخ التفعيل</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $subscriptionRequest->subscription->start_date->format('Y-m-d') }}</dd>
                    </div>
                    <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">الأجهزة المسجلة</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            {{ $subscriptionRequest->subscription->devices->count() }}/{{ $subscriptionRequest->subscription->device_count }}
                        </dd>
                    </div>
                </dl>
            </div>
        </div>
        @endif

        <!-- Action Buttons -->
        <div class="flex justify-between">
            <a href="{{ route('admin.subscription-requests.index') }}" 
               class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                العودة للقائمة
            </a>
        </div>
    </div>

    <!-- Reject Modal -->
    <div id="rejectModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3 text-center">
                <h3 class="text-lg font-medium text-gray-900">رفض الطلب</h3>
                <form method="POST" action="{{ route('admin.subscription-requests.reject', $subscriptionRequest->id) }}" class="mt-4">
                    @csrf
                    <div class="mb-4">
                        <label for="admin_notes" class="block text-sm font-medium text-gray-700 mb-2">
                            سبب الرفض (مطلوب)
                        </label>
                        <textarea id="admin_notes" 
                                  name="admin_notes" 
                                  rows="4" 
                                  class="w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500"
                                  placeholder="يرجى توضيح سبب رفض الطلب..."
                                  required></textarea>
                    </div>
                    <div class="flex justify-between">
                        <button type="button" 
                                onclick="hideRejectModal()"
                                class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                            إلغاء
                        </button>
                        <button type="submit" 
                                class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                            رفض الطلب
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function showRejectModal() {
            document.getElementById('rejectModal').classList.remove('hidden');
        }

        function hideRejectModal() {
            document.getElementById('rejectModal').classList.add('hidden');
        }
    </script>
</body>
</html>