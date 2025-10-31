<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>إعدادات النظام - إدارة الاشتراكات</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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
                    <h1 class="text-2xl font-bold text-gray-900">إعدادات النظام</h1>
                </div>
                
                <div class="flex items-center space-x-3 space-x-reverse">
                    <div class="flex items-center text-sm text-gray-600">
                        <i class="fas fa-user-cog ml-1"></i>
                        مدير النظام: {{ auth()->user()->name }}
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        
        <!-- Settings Cards Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-6">
            
            <!-- General Settings -->
            <div class="bg-white shadow-lg rounded-lg overflow-hidden">
                <div class="px-6 py-4 bg-gradient-to-r from-blue-500 to-indigo-600">
                    <h3 class="text-lg font-medium text-white flex items-center">
                        <i class="fas fa-cogs ml-2"></i>
                        الإعدادات العامة
                    </h3>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">اسم التطبيق</label>
                            <input type="text" value="إدارة الاشتراكات" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">وصف التطبيق</label>
                            <textarea class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-blue-500 focus:border-blue-500 rows-3">نظام إدارة الاشتراكات للأجهزة الذكية</textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">المنطقة الزمنية</label>
                            <select class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="Africa/Cairo" selected>القاهرة (GMT+2)</option>
                                <option value="Asia/Dubai">دبي (GMT+4)</option>
                                <option value="Asia/Riyadh">الرياض (GMT+3)</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Security Settings -->
            <div class="bg-white shadow-lg rounded-lg overflow-hidden">
                <div class="px-6 py-4 bg-gradient-to-r from-red-500 to-pink-600">
                    <h3 class="text-lg font-medium text-white flex items-center">
                        <i class="fas fa-shield-alt ml-2"></i>
                        إعدادات الأمان
                    </h3>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-gray-700">تفعيل التحقق الثنائي</span>
                            <button class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent bg-gray-200 transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2" onclick="toggleSetting(this)">
                                <span class="translate-x-0 inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out"></span>
                            </button>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-gray-700">تسجيل العمليات</span>
                            <button class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent bg-indigo-600 transition-colors duration-200 ease-in-out focus:outline-none" onclick="toggleSetting(this)">
                                <span class="translate-x-5 inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out"></span>
                            </button>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-gray-700">انتهاء الجلسة التلقائي</span>
                            <button class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent bg-indigo-600 transition-colors duration-200 ease-in-out focus:outline-none" onclick="toggleSetting(this)">
                                <span class="translate-x-5 inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out"></span>
                            </button>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">مدة الجلسة (دقيقة)</label>
                            <input type="number" value="120" min="30" max="480" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Email Settings -->
            <div class="bg-white shadow-lg rounded-lg overflow-hidden">
                <div class="px-6 py-4 bg-gradient-to-r from-green-500 to-teal-600">
                    <h3 class="text-lg font-medium text-white flex items-center">
                        <i class="fas fa-envelope ml-2"></i>
                        إعدادات البريد الإلكتروني
                    </h3>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">خادم SMTP</label>
                            <input type="text" value="smtp.gmail.com" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">المنفذ</label>
                                <input type="number" value="587" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">التشفير</label>
                                <select class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-blue-500 focus:border-blue-500">
                                    <option value="tls" selected>TLS</option>
                                    <option value="ssl">SSL</option>
                                </select>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">البريد الإلكتروني</label>
                            <input type="email" placeholder="admin@example.com" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Payment Settings -->
            <div class="bg-white shadow-lg rounded-lg overflow-hidden">
                <div class="px-6 py-4 bg-gradient-to-r from-yellow-500 to-orange-600">
                    <h3 class="text-lg font-medium text-white flex items-center">
                        <i class="fas fa-credit-card ml-2"></i>
                        إعدادات المدفوعات
                    </h3>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-gray-700">التحقق التلقائي من المدفوعات</span>
                            <button class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent bg-gray-200 transition-colors duration-200 ease-in-out focus:outline-none" onclick="toggleSetting(this)">
                                <span class="translate-x-0 inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out"></span>
                            </button>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">العملة الافتراضية</label>
                            <select class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="EGP" selected>جنيه مصري (ج.م)</option>
                                <option value="USD">دولار أمريكي ($)</option>
                                <option value="SAR">ريال سعودي (ر.س)</option>
                                <option value="AED">درهم إماراتي (د.إ)</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">الحد الأدنى للدفع</label>
                            <input type="number" value="50" min="1" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">طرق الدفع المتاحة</label>
                            <div class="space-y-2">
                                <label class="flex items-center">
                                    <input type="checkbox" checked class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                    <span class="mr-2 text-sm text-gray-700">تحويل بنكي</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" checked class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                    <span class="mr-2 text-sm text-gray-700">فيزا/ماستركارد</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" checked class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                    <span class="mr-2 text-sm text-gray-700">فودافون كاش</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- System Maintenance -->
            <div class="bg-white shadow-lg rounded-lg overflow-hidden">
                <div class="px-6 py-4 bg-gradient-to-r from-purple-500 to-indigo-600">
                    <h3 class="text-lg font-medium text-white flex items-center">
                        <i class="fas fa-tools ml-2"></i>
                        صيانة النظام
                    </h3>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <button class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded-md transition-colors">
                            <i class="fas fa-broom ml-1"></i>
                            مسح الذاكرة المؤقتة
                        </button>
                        <button class="w-full bg-green-600 hover:bg-green-700 text-white py-2 px-4 rounded-md transition-colors">
                            <i class="fas fa-database ml-1"></i>
                            نسخ احتياطي للقاعدة
                        </button>
                        <button class="w-full bg-orange-600 hover:bg-orange-700 text-white py-2 px-4 rounded-md transition-colors">
                            <i class="fas fa-sync ml-1"></i>
                            تحديث النظام
                        </button>
                        <button class="w-full bg-red-600 hover:bg-red-700 text-white py-2 px-4 rounded-md transition-colors">
                            <i class="fas fa-power-off ml-1"></i>
                            وضع الصيانة
                        </button>
                    </div>
                    
                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <h4 class="font-medium text-gray-900 mb-3">إحصائيات النظام</h4>
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-600">حجم قاعدة البيانات:</span>
                                <span class="text-gray-900 font-medium">25.6 MB</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">استخدام الذاكرة:</span>
                                <span class="text-gray-900 font-medium">64 MB</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">إصدار Laravel:</span>
                                <span class="text-gray-900 font-medium">10.x</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">آخر نسخ احتياطي:</span>
                                <span class="text-gray-900 font-medium">اليوم 14:30</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Notification Settings -->
            <div class="bg-white shadow-lg rounded-lg overflow-hidden">
                <div class="px-6 py-4 bg-gradient-to-r from-pink-500 to-red-600">
                    <h3 class="text-lg font-medium text-white flex items-center">
                        <i class="fas fa-bell ml-2"></i>
                        إعدادات الإشعارات
                    </h3>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-gray-700">إشعارات الدفع الجديدة</span>
                            <button class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent bg-indigo-600 transition-colors duration-200 ease-in-out focus:outline-none" onclick="toggleSetting(this)">
                                <span class="translate-x-5 inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out"></span>
                            </button>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-gray-700">إشعارات العملاء الجدد</span>
                            <button class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent bg-indigo-600 transition-colors duration-200 ease-in-out focus:outline-none" onclick="toggleSetting(this)">
                                <span class="translate-x-5 inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out"></span>
                            </button>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-gray-700">إشعارات انتهاء الاشتراكات</span>
                            <button class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent bg-indigo-600 transition-colors duration-200 ease-in-out focus:outline-none" onclick="toggleSetting(this)">
                                <span class="translate-x-5 inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out"></span>
                            </button>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-gray-700">إشعارات البريد الإلكتروني</span>
                            <button class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent bg-gray-200 transition-colors duration-200 ease-in-out focus:outline-none" onclick="toggleSetting(this)">
                                <span class="translate-x-0 inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out"></span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- Save Button -->
        <div class="mt-8 text-center">
            <button class="bg-blue-600 hover:bg-blue-700 text-white py-3 px-8 rounded-lg font-medium text-lg transition-colors shadow-lg">
                <i class="fas fa-save ml-2"></i>
                حفظ جميع الإعدادات
            </button>
        </div>

    </main>

    <script>
        // Setup CSRF token for all AJAX requests
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        function toggleSetting(element) {
            const isEnabled = element.classList.contains('bg-indigo-600');
            
            if (isEnabled) {
                // Disable
                element.classList.remove('bg-indigo-600');
                element.classList.add('bg-gray-200');
                element.querySelector('span').classList.remove('translate-x-5');
                element.querySelector('span').classList.add('translate-x-0');
            } else {
                // Enable
                element.classList.remove('bg-gray-200');
                element.classList.add('bg-indigo-600');
                element.querySelector('span').classList.remove('translate-x-0');
                element.querySelector('span').classList.add('translate-x-5');
            }
        }

        // Auto-save indicators
        $(document).ready(function() {
            $('input, select, textarea').on('change', function() {
                // Show unsaved changes indicator
                const indicator = $('<span class="text-orange-500 text-xs ml-1">• غير محفوظ</span>');
                $(this).parent().find('.unsaved-indicator').remove();
                $(this).parent().append(indicator.addClass('unsaved-indicator'));
            });
        });
    </script>
</body>
</html>