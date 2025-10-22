<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>نظام إدارة الاشتراكات</title>
    
    <!-- Tailwind CSS CDN for quick styling -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font -->
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700&display=swap" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Tajawal', sans-serif;
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">
    <!-- Navigation Header -->
    <header class="bg-white shadow-sm border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center">
                    <h1 class="text-2xl font-bold text-blue-600">إدارة الاشتراكات</h1>
                </div>
                <div class="flex items-center space-x-4 space-x-reverse">
                    @guest
                        <a href="{{ route('login') }}" class="text-gray-600 hover:text-blue-600 px-3 py-2 rounded-md">تسجيل الدخول</a>
                        <a href="{{ route('register') }}" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition">إنشاء حساب</a>
                    @else
                        <div class="flex items-center space-x-4 space-x-reverse">
                            <span class="text-gray-700">مرحباً، {{ auth()->user()->name }}</span>
                            <a href="{{ route(auth()->user()->getDashboardRoute()) }}" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition">لوحة التحكم</a>
                            <form method="POST" action="{{ route('logout') }}" class="inline">
                                @csrf
                                <button type="submit" class="text-gray-600 hover:text-red-600 px-3 py-2 rounded-md">تسجيل الخروج</button>
                            </form>
                        </div>
                    @endguest
                </div>
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="bg-gradient-to-l from-blue-600 to-blue-800 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24">
            <div class="text-center">
                <h2 class="text-4xl md:text-6xl font-bold mb-6">
                    نظام إدارة الاشتراكات المتطور
                </h2>
                <p class="text-xl md:text-2xl mb-8 text-blue-100 max-w-3xl mx-auto">
                    حلول شاملة لإدارة أعمالك وتنظيم اشتراكاتك بطريقة احترافية وآمنة
                </p>
                @guest
                    <div class="flex flex-col sm:flex-row gap-4 justify-center">
                        <a href="{{ route('register') }}" class="bg-white text-blue-600 px-8 py-4 rounded-lg font-semibold hover:bg-gray-100 transition">
                            ابدأ مجاناً الآن
                        </a>
                        <a href="#services" class="border-2 border-white text-white px-8 py-4 rounded-lg font-semibold hover:bg-white hover:text-blue-600 transition">
                            تصفح الخدمات
                        </a>
                    </div>
                @else
                    <a href="{{ route(auth()->user()->getDashboardRoute()) }}" class="inline-block bg-white text-blue-600 px-8 py-4 rounded-lg font-semibold hover:bg-gray-100 transition">
                        انتقل إلى لوحة التحكم
                    </a>
                @endguest
            </div>
        </div>
    </section>

    <!-- Services Section -->
    <section id="services" class="py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h3 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                    اختر الباقة المناسبة لك
                </h3>
                <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                    نوفر لك مجموعة متنوعة من الباقات لتناسب احتياجات مشروعك
                </p>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                @foreach($services as $index => $service)
                    <div class="bg-white rounded-xl shadow-lg hover:shadow-xl transition duration-300 overflow-hidden {{ $index === 1 ? 'border-2 border-blue-500 transform scale-105' : '' }}">
                        @if($index === 1)
                            <div class="bg-blue-500 text-white text-center py-3 font-semibold">
                                الأكثر شعبية
                            </div>
                        @endif
                        
                        <div class="p-8">
                            <div class="text-center mb-8">
                                <h4 class="text-2xl font-bold text-gray-900 mb-2">{{ $service['title'] }}</h4>
                                <p class="text-gray-600 mb-6">{{ $service['description'] }}</p>
                                <div class="mb-6">
                                    <span class="text-4xl font-bold text-blue-600">{{ $service['price'] }}</span>
                                    <span class="text-gray-600 mr-2">{{ $service['currency'] }}</span>
                                    <span class="text-gray-500">/ {{ $service['period'] }}</span>
                                </div>
                            </div>

                            <ul class="space-y-4 mb-8">
                                @foreach($service['features'] as $feature)
                                    <li class="flex items-center">
                                        <svg class="w-5 h-5 text-green-500 ml-3" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                        </svg>
                                        <span class="text-gray-700">{{ $feature }}</span>
                                    </li>
                                @endforeach
                            </ul>

                            @guest
                                <a href="{{ route('register') }}" class="w-full block text-center {{ $index === 1 ? 'bg-blue-600 hover:bg-blue-700' : 'bg-gray-800 hover:bg-gray-900' }} text-white py-3 rounded-lg font-semibold transition">
                                    اختر هذه الباقة
                                </a>
                            @else
                                <a href="{{ route(auth()->user()->getDashboardRoute()) }}" class="w-full block text-center {{ $index === 1 ? 'bg-blue-600 hover:bg-blue-700' : 'bg-gray-800 hover:bg-gray-900' }} text-white py-3 rounded-lg font-semibold transition">
                                    إدارة الاشتراك
                                </a>
                            @endguest
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="bg-gray-100 py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h3 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                    لماذا تختار نظامنا؟
                </h3>
                <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                    نحن نقدم أفضل الحلول المتقدمة لإدارة أعمالك بكفاءة عالية
                </p>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
                <div class="text-center">
                    <div class="bg-blue-600 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h4 class="text-xl font-semibold text-gray-900 mb-2">سهولة الاستخدام</h4>
                    <p class="text-gray-600">واجهة بسيطة ومفهومة لجميع مستويات المستخدمين</p>
                </div>

                <div class="text-center">
                    <div class="bg-blue-600 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                    </div>
                    <h4 class="text-xl font-semibold text-gray-900 mb-2">أمان عالي</h4>
                    <p class="text-gray-600">حماية متقدمة لبياناتك مع تشفير عالي المستوى</p>
                </div>

                <div class="text-center">
                    <div class="bg-blue-600 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <h4 class="text-xl font-semibold text-gray-900 mb-2">أداء سريع</h4>
                    <p class="text-gray-600">سرعة عالية في المعالجة والاستجابة</p>
                </div>

                <div class="text-center">
                    <div class="bg-blue-600 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192L5.636 18.364M12 12h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h4 class="text-xl font-semibold text-gray-900 mb-2">دعم فني متميز</h4>
                    <p class="text-gray-600">فريق دعم متخصص متاح على مدار الساعة</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h4 class="text-2xl font-bold mb-4">نظام إدارة الاشتراكات</h4>
                <p class="text-gray-400 mb-6">الحل الأمثل لإدارة أعمالك بكفاءة واحترافية</p>
                @guest
                    <div class="flex flex-col sm:flex-row gap-4 justify-center">
                        <a href="{{ route('register') }}" class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition">
                            ابدأ الآن
                        </a>
                        <a href="{{ route('login') }}" class="border border-gray-600 text-gray-300 px-6 py-3 rounded-lg hover:border-gray-400 hover:text-white transition">
                            تسجيل الدخول
                        </a>
                    </div>
                @endguest
                <div class="mt-8 pt-8 border-t border-gray-800">
                    <p class="text-gray-500">&copy; {{ date('Y') }} جميع الحقوق محفوظة - نظام إدارة الاشتراكات</p>
                </div>
            </div>
        </div>
    </footer>

    <script>
        // Smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                document.querySelector(this.getAttribute('href')).scrollIntoView({
                    behavior: 'smooth'
                });
            });
        });
    </script>
</body>
</html>