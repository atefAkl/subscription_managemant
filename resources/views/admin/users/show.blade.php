<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>تفاصيل المستخدم - {{ $user->name }}</title>
    
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
    
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body class="bg-gray-100 min-h-screen">
    <!-- Navigation -->
    <nav class="bg-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <div class="flex-shrink-0 flex items-center">
                        <h1 class="text-xl font-bold text-blue-600">تفاصيل المستخدم - {{ $user->name }}</h1>
                    </div>
                </div>
                
                <div class="flex items-center space-x-4 space-x-reverse">
                    <a href="{{ route('admin.users.index') }}" class="text-gray-600 hover:text-blue-600 px-3 py-2 rounded-md text-sm">
                        إدارة المستخدمين
                    </a>
                    <a href="{{ route('admin.dashboard') }}" class="text-gray-600 hover:text-blue-600 px-3 py-2 rounded-md text-sm">
                        لوحة الإدارة
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
    </nav>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <!-- Breadcrumb -->
        <nav class="flex mb-8" aria-label="Breadcrumb">
            <ol class="flex items-center space-x-2 space-x-reverse">
                <li>
                    <a href="{{ route('admin.dashboard') }}" class="text-gray-400 hover:text-gray-500">
                        لوحة الإدارة
                    </a>
                </li>
                <li class="flex items-center">
                    <svg class="flex-shrink-0 w-5 h-5 text-gray-300 mx-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 111.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                    </svg>
                    <a href="{{ route('admin.users.index') }}" class="text-gray-400 hover:text-gray-500">
                        إدارة المستخدمين
                    </a>
                </li>
                <li class="flex items-center">
                    <svg class="flex-shrink-0 w-5 h-5 text-gray-300 mx-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 111.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="text-gray-500">
                        {{ $user->name }}
                    </span>
                </li>
            </ol>
        </nav>

        <!-- Header with Actions -->
        <div class="md:flex md:items-center md:justify-between mb-8">
            <div class="flex-1 min-w-0">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="h-16 w-16 rounded-full bg-gradient-to-r from-blue-500 to-purple-500 flex items-center justify-center">
                            <span class="text-2xl font-bold text-white">
                                {{ substr($user->name, 0, 2) }}
                            </span>
                        </div>
                    </div>
                    <div class="mr-4">
                        <h2 class="text-3xl font-bold leading-7 text-gray-900">
                            {{ $user->name }}
                        </h2>
                        <p class="text-lg text-gray-500">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                {{ $user->role === 'admin' ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                                @if($user->role === 'admin')
                                    <i class="fas fa-user-tie ml-1"></i> مدير
                                @else
                                    <i class="fas fa-building ml-1"></i> عميل
                                @endif
                            </span>
                        </p>
                    </div>
                </div>
            </div>
            <div class="mt-4 md:mt-0 flex space-x-3 space-x-reverse">
                <a href="{{ route('admin.users.edit', $user) }}" 
                   class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                    <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    تعديل المستخدم
                </a>
                @if($user->id !== auth()->id())
                    <button onclick="deleteUser()" 
                            class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700">
                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                        حذف المستخدم
                    </button>
                @endif
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- User Information -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Basic Info -->
                <div class="bg-white shadow-xl overflow-hidden sm:rounded-lg">
                    <div class="px-6 py-5 border-b border-gray-200">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">
                            المعلومات الأساسية
                        </h3>
                    </div>
                    <div class="px-6 py-6">
                        <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">الاسم الكامل</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $user->name }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">البريد الإلكتروني</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $user->email }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">نوع المستخدم</dt>
                                <dd class="mt-1">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        {{ $user->role === 'admin' ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                                        @if($user->role === 'admin')
                                            <i class="fas fa-user-tie ml-1"></i> مدير
                                        @else
                                            <i class="fas fa-building ml-1"></i> عميل
                                        @endif
                                    </span>
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">تاريخ التسجيل</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $user->created_at->format('Y-m-d H:i') }}</dd>
                            </div>
                            @if($user->phone)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">رقم الهاتف</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $user->phone }}</dd>
                                </div>
                            @endif
                            <div>
                                <dt class="text-sm font-medium text-gray-500">آخر تسجيل دخول</dt>
                                <dd class="mt-1 text-sm text-gray-900">
                                    {{ $user->last_login_at ? $user->last_login_at->diffForHumans() : 'لم يسجل دخول بعد' }}
                                </dd>
                            </div>
                            @if($user->address)
                                <div class="sm:col-span-2">
                                    <dt class="text-sm font-medium text-gray-500">العنوان</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $user->address }}</dd>
                                </div>
                            @endif
                            @if($user->notes)
                                <div class="sm:col-span-2">
                                    <dt class="text-sm font-medium text-gray-500">ملاحظات</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $user->notes }}</dd>
                                </div>
                            @endif
                        </dl>
                    </div>
                </div>

                <!-- Subscriptions (for clients) -->
                @if($user->role === 'client' && $user->subscriptions->count() > 0)
                    <div class="bg-white shadow-xl overflow-hidden sm:rounded-lg">
                        <div class="px-6 py-5 border-b border-gray-200">
                            <h3 class="text-lg leading-6 font-medium text-gray-900">
                                الاشتراكات
                            </h3>
                        </div>
                        <div class="overflow-hidden">
                            <ul class="divide-y divide-gray-200">
                                @foreach($user->subscriptions as $subscription)
                                    <li class="px-6 py-4">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0">
                                                    <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                                        <span class="text-blue-600 text-sm">📡</span>
                                                    </div>
                                                </div>
                                                <div class="mr-4">
                                                    <div class="text-sm font-medium text-gray-900">
                                                        اشتراك {{ $subscription->name }}
                                                    </div>
                                                    <div class="text-sm text-gray-500">
                                                        ID: {{ $subscription->id }}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="text-left">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                    {{ $subscription->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                    {{ $subscription->status === 'active' ? '🟢 نشط' : '🔴 غير نشط' }}
                                                </span>
                                                <div class="text-xs text-gray-500 mt-1">
                                                    {{ $subscription->created_at->format('Y-m-d') }}
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif

                <!-- Activities Log -->
                <div class="bg-white shadow-xl overflow-hidden sm:rounded-lg">
                    <div class="px-6 py-5 border-b border-gray-200">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">
                            سجل النشاطات الأخيرة
                        </h3>
                    </div>
                    <div class="px-6 py-6">
                        <div id="activitiesContent">
                            <div class="text-center py-8">
                                <div class="text-gray-400 text-4xl mb-2">⏳</div>
                                <p class="text-gray-500">جار تحميل النشاطات...</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistics Sidebar -->
            <div class="space-y-6">
                <!-- Quick Stats -->
                <div class="bg-white shadow-xl overflow-hidden sm:rounded-lg">
                    <div class="px-6 py-5 border-b border-gray-200">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">
                            إحصائيات سريعة
                        </h3>
                    </div>
                    <div class="px-6 py-6 space-y-4">
                        @if($user->role === 'client')
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-500">الاشتراكات</span>
                                <span class="text-lg font-semibold text-blue-600">{{ $user->subscriptions->count() }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-500">الأجهزة</span>
                                <span class="text-lg font-semibold text-green-600">{{ $user->subscriptions->sum('devices_count') ?? 0 }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-500">المدفوعات</span>
                                <span class="text-lg font-semibold text-purple-600">{{ $user->payments->count() ?? 0 }}</span>
                            </div>
                        @else
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-500">المستخدمين المُدارين</span>
                                <span class="text-lg font-semibold text-blue-600">{{ $managedUsersCount ?? 0 }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-500">العمليات اليوم</span>
                                <span class="text-lg font-semibold text-green-600">{{ $todayOperations ?? 0 }}</span>
                            </div>
                        @endif
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-500">مدة العضوية</span>
                            <span class="text-lg font-semibold text-indigo-600">{{ $user->created_at->diffInDays(now()) }} يوم</span>
                        </div>
                    </div>
                </div>

                <!-- Account Status -->
                <div class="bg-white shadow-xl overflow-hidden sm:rounded-lg">
                    <div class="px-6 py-5 border-b border-gray-200">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">
                            حالة الحساب
                        </h3>
                    </div>
                    <div class="px-6 py-6 space-y-3">
                        <div class="flex items-center">
                            <i class="fas fa-check-circle text-green-500 text-xl"></i>
                            <span class="mr-2 text-sm text-gray-700">حساب نشط</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-check-circle text-green-500 text-xl"></i>
                            <span class="mr-2 text-sm text-gray-700">البريد الإلكتروني مؤكد</span>
                        </div>
                        <div class="flex items-center">
                            <span class="text-blue-500 text-xl">🔐</span>
                            <span class="mr-2 text-sm text-gray-700">كلمة مرور آمنة</span>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="bg-white shadow-xl overflow-hidden sm:rounded-lg">
                    <div class="px-6 py-5 border-b border-gray-200">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">
                            إجراءات سريعة
                        </h3>
                    </div>
                    <div class="px-6 py-6 space-y-3">
                        <button onclick="sendPasswordReset()" class="w-full text-left text-sm text-blue-600 hover:text-blue-800">
                            <i class="fas fa-envelope ml-1"></i> إرسال رابط إعادة تعيين كلمة المرور
                        </button>
                        @if($user->role === 'client')
                            <a href="#" class="block text-sm text-green-600 hover:text-green-800">
                                <i class="fas fa-chart-bar ml-1"></i> عرض تقرير العميل
                            </a>
                        @endif
                        <button onclick="exportUserData()" class="w-full text-left text-sm text-purple-600 hover:text-purple-800">
                            <i class="fas fa-download ml-1"></i> تصدير بيانات المستخدم
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full m-4">
            <div class="px-6 py-4">
                <h3 class="text-lg font-medium text-gray-900 mb-4">تأكيد حذف المستخدم</h3>
                <p class="text-gray-500 mb-6">هل أنت متأكد من حذف المستخدم <strong>{{ $user->name }}</strong>؟ لا يمكن التراجع عن هذا الإجراء.</p>
                <div class="flex justify-end space-x-3 space-x-reverse">
                    <button onclick="hideDeleteModal()" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                        إلغاء
                    </button>
                    <form method="POST" action="{{ route('admin.users.destroy', $user) }}" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                            تأكيد الحذف
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Load user activities
        document.addEventListener('DOMContentLoaded', function() {
            loadUserActivities();
        });

        function loadUserActivities() {
            fetch(`/admin/users/{{ $user->id }}/activities`)
                .then(response => response.json())
                .then(activities => {
                    const content = activities.length > 0 
                        ? activities.map(activity => `
                            <div class="border-b border-gray-200 pb-3 mb-3 last:border-b-0">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <h4 class="font-medium text-gray-900">${activity.description}</h4>
                                        <p class="text-sm text-gray-500">${activity.type}</p>
                                    </div>
                                    <div class="text-left">
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            ${activity.status}
                                        </span>
                                        <div class="text-xs text-gray-500 mt-1">${new Date(activity.date).toLocaleDateString('ar')}</div>
                                    </div>
                                </div>
                            </div>
                        `).join('')
                        : '<div class="text-center py-8"><div class="text-gray-400 text-4xl mb-2"><i class="fas fa-chart-line"></i></div><p class="text-gray-500">لا توجد نشاطات مسجلة</p></div>';
                    
                    document.getElementById('activitiesContent').innerHTML = content;
                })
                .catch(error => {
                    console.error('Error:', error);
                    document.getElementById('activitiesContent').innerHTML = 
                        '<div class="text-center py-8"><div class="text-red-400 text-4xl mb-2"><i class="fas fa-exclamation-triangle"></i></div><p class="text-red-500">حدث خطأ في تحميل النشاطات</p></div>';
                });
        }

        function deleteUser() {
            document.getElementById('deleteModal').classList.remove('hidden');
            document.getElementById('deleteModal').classList.add('flex');
        }

        function hideDeleteModal() {
            document.getElementById('deleteModal').classList.add('hidden');
            document.getElementById('deleteModal').classList.remove('flex');
        }

        function sendPasswordReset() {
            if (confirm('هل تريد إرسال رابط إعادة تعيين كلمة المرور لهذا المستخدم؟')) {
                // Here you would implement the password reset functionality
                showMessage('تم إرسال رابط إعادة تعيين كلمة المرور بنجاح', 'success');
            }
        }

        function exportUserData() {
            // Here you would implement the export functionality
            showMessage('جار تصدير بيانات المستخدم...', 'info');
        }

        function showMessage(message, type) {
            const messageDiv = document.createElement('div');
            const bgColor = type === 'success' ? 'bg-green-100 text-green-700 border border-green-400' 
                          : type === 'error' ? 'bg-red-100 text-red-700 border border-red-400'
                          : 'bg-blue-100 text-blue-700 border border-blue-400';
            
            messageDiv.className = `fixed top-4 right-4 z-50 p-4 rounded-md ${bgColor}`;
            messageDiv.textContent = message;
            
            document.body.appendChild(messageDiv);
            
            setTimeout(() => {
                messageDiv.remove();
            }, 5000);
        }
    </script>
</body>
</html>