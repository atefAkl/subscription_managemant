<!DOCTYPE html>
<html lang="ar" dir="rtl">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>@yield('title' ,config('app.name', 'إدارة الاشتراكات')) - لوحة بيانات العملاء</title>

        <script src="https://cdn.tailwindcss.com"></script>
        <!-- Bootstrap CSS RTL -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css" rel="stylesheet">

        <!-- jQuery -->
        <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

        <!-- FontAwesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

        <!-- Arabic Font -->
        <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700&display=swap" rel="stylesheet">

        <link href="{{ asset('css/client-styles.css') }}" rel="stylesheet">


    </head>

    <body class="bg-gray-100">
        <!-- Navigation -->
        <nav class="bg-white shadow-sm fixed-top z-50">
            <div class="max-w-7xl mx-auto px-4">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 flex items-center">
                            <h1 class="text-xl font-bold text-blue-600">لوحة إدارة العملاء</h1>
                        </div>
                    </div>

                    <div class="flex items-center space-x-4 space-x-reverse">
                        <div class="relative">
                            <span class="text-gray-700">مرحباً، {{ Auth::user()->name }}</span>
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
        <div class="max-w-7xl mx-auto mt-12 py-6 sm:px-6 lg:px-8">

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

            @if($errors->any())
            <div class="container pt-3">
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>يرجى تصحيح الأخطاء التالية:</strong>
                    <ul class="mb-0 mt-2">
                        @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            </div>
            @endif
            <div class="container pt-3">
                <!-- Page Content -->
                @yield('content')
            </div>
            </main>
            <!-- Bootstrap Bundle JS -->
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

            <!-- FontAwesome JS -->
            <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>

            <!-- Custom Scripts -->
            <script>
                // CSRF Token Setup
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        
        // Auto-hide alerts after 5 seconds
        setTimeout(function() {
            $('.alert').fadeOut('slow');
        }, 25000);
        
        // Loading spinner helper
        function showLoading(button) {
            button.html('<span class="spinner-border spinner-border-sm me-2"></span>جاري التحميل...');
            button.prop('disabled', true);
        }
        
        function hideLoading(button, originalText) {
            button.html(originalText);
            button.prop('disabled', false);
        }
            </script>

            @stack('scripts')
            @yield('scripts')

    </body>

</html>