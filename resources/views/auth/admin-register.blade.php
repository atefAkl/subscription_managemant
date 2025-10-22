<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>إضافة مستخدم جديد - نظام إدارة الاشتراكات</title>
    
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
    <nav class="bg-white shadow-lg mb-6">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <div class="flex-shrink-0 flex items-center">
                        <h1 class="text-xl font-bold text-blue-600">إضافة مستخدم جديد</h1>
                    </div>
                </div>
                
                <div class="flex items-center space-x-4 space-x-reverse">
                    <div class="relative">
                        <span class="text-gray-700">مرحباً، {{ auth()->user()->name }}</span>
                        <span class="inline-block bg-red-100 text-red-800 text-xs px-2 py-1 rounded-full mr-2">مدير</span>
                    </div>
                    
                    <div class="flex space-x-2 space-x-reverse">
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
        </div>
    </nav>

    <div class="max-w-md mx-auto px-4">
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="px-6 py-4 bg-blue-600 text-white">
                <div class="flex items-center">
                    <div class="w-8 h-8 bg-white bg-opacity-20 rounded-full flex items-center justify-center ml-3">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-lg font-semibold">إضافة مستخدم جديد</h2>
                        <p class="text-sm text-blue-100">يمكنك إضافة مديرين أو عملاء جدد</p>
                    </div>
                </div>
            </div>
            
            <div class="p-6">
                @if($errors->any())
                    <div class="mb-4 bg-red-50 border border-red-200 rounded-md p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="mr-3">
                                <h3 class="text-sm font-medium text-red-800">يرجى تصحيح الأخطاء التالية:</h3>
                                <div class="mt-2 text-sm text-red-700">
                                    <ul class="list-disc list-inside space-y-1">
                                        @foreach($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <form action="{{ route('admin.register') }}" method="POST" class="space-y-4">
                    @csrf
                    
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
                            الاسم الكامل
                        </label>
                        <input id="name" name="name" type="text" autocomplete="name" required 
                               value="{{ old('name') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                               placeholder="أدخل الاسم الكامل">
                    </div>
                    
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                            البريد الإلكتروني
                        </label>
                        <input id="email" name="email" type="email" autocomplete="email" required 
                               value="{{ old('email') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                               placeholder="أدخل البريد الإلكتروني">
                    </div>

                    <div>
                        <label for="role" class="block text-sm font-medium text-gray-700 mb-1">
                            نوع المستخدم
                        </label>
                        <select id="role" name="role" required 
                                class="w-full px-3 py-2 border border-gray-300 bg-white rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">اختر نوع المستخدم</option>
                            <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>
                                <span class="flex items-center">
                                    🛡️ مدير النظام
                                </span>
                            </option>
                            <option value="client" {{ old('role') == 'client' ? 'selected' : '' }}>
                                <span class="flex items-center">
                                    👤 عميل
                                </span>
                            </option>
                        </select>
                        <p class="mt-1 text-xs text-gray-500">
                            المديرون لديهم صلاحيات كاملة، العملاء لديهم صلاحيات محدودة
                        </p>
                    </div>
                    
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
                            كلمة المرور
                        </label>
                        <input id="password" name="password" type="password" autocomplete="new-password" required 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                               placeholder="أدخل كلمة المرور">
                    </div>
                    
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">
                            تأكيد كلمة المرور
                        </label>
                        <input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" required 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                               placeholder="أعد إدخال كلمة المرور">
                    </div>

                    <div class="flex items-start">
                        <div class="flex items-center h-5">
                            <input id="send_email" name="send_email" type="checkbox" 
                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        </div>
                        <div class="mr-2 text-sm">
                            <label for="send_email" class="font-medium text-gray-700">
                                إرسال بيانات الدخول بالبريد الإلكتروني
                            </label>
                            <p class="text-gray-500">سيتم إرسال كلمة المرور للمستخدم الجديد</p>
                        </div>
                    </div>

                    <div class="flex gap-3 pt-4">
                        <button type="submit" 
                                class="flex-1 bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition duration-150 ease-in-out">
                            <span class="flex items-center justify-center">
                                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                إضافة المستخدم
                            </span>
                        </button>
                        
                        <a href="{{ route('admin.dashboard') }}" 
                           class="flex-1 bg-gray-500 text-white py-2 px-4 rounded-md hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition duration-150 ease-in-out text-center">
                            إلغاء
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="mt-6 grid grid-cols-2 gap-4">
            <div class="bg-white rounded-lg shadow p-4 text-center">
                <div class="text-2xl font-bold text-blue-600">{{ \App\Models\User::where('role', 'admin')->count() }}</div>
                <div class="text-sm text-gray-500">مديرون</div>
            </div>
            <div class="bg-white rounded-lg shadow p-4 text-center">
                <div class="text-2xl font-bold text-green-600">{{ \App\Models\User::where('role', 'client')->count() }}</div>
                <div class="text-sm text-gray-500">عملاء</div>
            </div>
        </div>
    </div>
</body>
</html>