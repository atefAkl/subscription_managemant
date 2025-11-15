<!DOCTYPE html>
<html lang="ar" dir="rtl">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>نظام إدارة الاشتراكات</title>


        <script src="https://cdn.tailwindcss.com"></script>
        <!-- Bootstrap CSS RTL -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css" rel="stylesheet">

        <!-- jQuery -->
        <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

        <!-- FontAwesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

        <!-- Arabic Font -->
        <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700&display=swap" rel="stylesheet">

        <!-- Popper.js for Tooltips & Popovers -->
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>

        <link href="{{ asset('css/custom-styles.css') }}" rel="stylesheet">
        <link href="{{ asset('css/override-styles.css') }}" rel="stylesheet">

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
                            <a href="{{ route(auth()->user()->getDashboardRoute()) }}" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition">لوحة
                                التحكم</a>
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
                    <a href="{{ route(auth()->user()->getDashboardRoute()) }}"
                        class="inline-block bg-white text-blue-600 px-8 py-4 rounded-lg font-semibold hover:bg-gray-100 transition">
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

                <div class="grid grid-cols-4">
                    @forelse ($services ?? [] as $package)
                    <div class="p-1">
                        <div class="card mb-4">
                            <div class="card-header">
                                <h1 class="card-title text-center py-3">{{ $package->name }}</h1>
                                <div>
                                    <p class="card-text text-center">{{ $package->price }} SAR / {{ $package->duration }} {{ $package->duration_unit}}</p>

                                </div>
                            </div>
                            <div class="card-body">
                                <p class="card-text">{{ $package->description }}</p>
                                <p class="card-text">{{ $package->status }}</p>
                            </div>
                            <div class="card-header">
                                <h5 class="card-title">الميزات</h5>
                            </div>
                            <div class="card-body">
                                سعر الاشتراك: {{ $package->price }}
                            </div>
                            <div class="card-header">
                                <h5 class="card-title">الميزات</h5>
                            </div>
                            <div class="card-body">
                                <table>
                                    @forelse ($package->values as $value)
                                    <tr style="border-block-end: 1px solid #ccc">
                                        <th class="text-end text-sm px-2">{{ $value->feature->name }}</th>
                                        <td>{{ $value->value }}</td>
                                    </tr>


                                    @empty
                                    <tr>
                                        <td colspan="2">لا يوجد ميزات

                                        </td>
                                    </tr>

                                    @endforelse
                                    <tr>
                                        <td colspan="2">
                                            <p class="card-text text-center pt-3">دعم غير محدود 24/7 عبى كل الياقات</p>
                                        </td>
                                    </tr>
                                </table>

                            </div>
                            <div class="card-footer d-flex justify-content-end">
                                <a href="{{ route('client.subscriptions.start-from-package', $package->id) }}" class="btn btn-primary">اشترك الآن</a>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="alert alert-info">لا يوجد باقات</div>
                    @endforelse
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
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
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
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192L5.636 18.364M12 12h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                </path>
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
        {{-- Bootstrap JS --}}
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    </body>

</html>