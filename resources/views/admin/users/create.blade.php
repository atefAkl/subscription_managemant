@extends('layouts.app')

<!-- Main Content -->

@section('content')
<!-- Breadcrumb -->
<div class="max-w-7xl mx-auto py-2 sm:px-6 lg:px-8">
    <div class="bg-white rounded shadow-sm px-4 py-2 mb-0 md:flex md:items-center md:justify-between">
        <x-breadcrumb :items="[
        ['title' => 'لوحة التحكم', 'url' => route('admin.dashboard')],
        ['title' => 'إدارة المستخدمين', 'url' => route('admin.users.index')],
        ['title' => 'إضافة مستخدم جديد']
    ]" />
    </div>
</div>

<!-- Main Content -->
<div class="max-w-7xl mx-auto py-2 sm:px-6 lg:px-8">
    <div class="bg-white rounded shadow-sm px-4 py-2 mb-3 md:flex md:items-center md:justify-between">
        <div>
            <h3 class="text-lg leading-6 font-medium text-gray-900">
                إضافة مسؤول جديد
            </h3>
            <p class="mt-1 max-w-2xl text-sm text-gray-500">
                إضافة مسؤول جديد للنظام
            </p>
        </div>
        <a href="{{ route('admin.users.index') }}"
            class="btn-icon inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
            <i class="fas fa-plus"></i>
            قائمة المسؤولين
        </a>
    </div>

    <!-- Form -->
    <div class="bg-white shadow-sm overflow-hidden sm:rounded-lg">
        <div class="px-6 py-2 border-b border-gray-200">
            <h3 class="text-lg leading-6 font-medium text-gray-900">
                معلومات المستخدم
            </h3>
            <p class="mt-1 max-w-2xl text-sm text-gray-500">
                يرجى ملء جميع الحقول المطلوبة
            </p>
        </div>

        <form action="{{ route('admin.users.store') }}" method="POST" class="px-6 space-y-3">
            @csrf

            <!-- Name -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-2">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">
                        الاسم الكامل *
                    </label>
                    <div class="mt-1 relative">
                        <input type="text" name="name" id="name" value="{{ old('name') }}"
                            class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('name') border-red-300 @enderror">
                        @error('name')
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                    clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        @enderror
                    </div>
                    @error('name')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">
                        اسم المستخدم *
                    </label>
                    <div class="mt-1 relative">
                        <input type="text" name="user_name" id="user_name" value="{{ old('user_name') }}"
                            class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('user_name') border-red-300 @enderror">
                        @error('user_name')
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                    clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        @enderror
                    </div>
                    @error('user_name')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>


                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">
                        البريد الإلكتروني *
                    </label>
                    <div class="mt-1 relative">
                        <input type="email" name="email" id="email" value="{{ old('email') }}"
                            class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('email') border-red-300 @enderror">
                        @error('email')
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                    clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        @enderror
                    </div>
                    @error('email')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Password -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">
                        كلمة المرور *
                    </label>
                    <div class="mt-1 relative">
                        <input type="password" name="password" id="password"
                            class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('password') border-red-300 @enderror">
                        @error('password')
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fa fa-eye-slash"></i>
                        </div>
                        @enderror
                    </div>
                    @error('password')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-sm text-gray-500">
                        يجب أن تتكون كلمة المرور من 8 أحرف على الأقل
                    </p>
                </div>

                <!-- Password Confirmation -->
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700">
                        تأكيد كلمة المرور *
                    </label>
                    <div class="mt-1">
                        <input type="password" name="password_confirmation" id="password_confirmation"
                            class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    </div>
                </div>
            </div>


            <input type="hidden" name="role" value="admin">

            <!-- Phone (Optional) -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-2">

                <div class="">
                    <label for="phone" class="block text-sm font-medium text-gray-700">
                        رقم الهاتف
                    </label>
                    <div class="mt-1">
                        <input type="tel" name="phone" id="phone" value="{{ old('phone') }}" placeholder="مثال: 01234567890"
                            class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('phone') border-red-300 @enderror">
                    </div>
                    @error('phone')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="">
                    <label for="phone" class="block text-sm font-medium text-gray-700">
                        مستوى الوصول *
                    </label>
                    <div class="mt-1">
                        <select name="access_level" id="access_level"
                            class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('phone') border-red-300 @enderror">
                            <option value="1">مدير عام</option>
                            <option value="2">مدير تطبيق</option>
                            <option value="3">مدخل البيانات</option>
                        </select>
                    </div>
                    @error('access_level')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Address (Optional) -->
            <div class="">
                <div class="form-floating mt-1">
                    <textarea name="address" id="address" placeholder=""
                        class="form-control block w-full px-3 pt-4 pb-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('address') border-red-300 @enderror">{{ old('address') }}</textarea>
                    <label for="address" class="form-label">
                        العنوان
                    </label>
                </div>
                @error('address')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Notes (Optional) -->
            <div>
                <div class="form-floating mt-1">
                    <textarea name="notes" id="notes" placeholder=""
                        class="form-control block w-full px-3 pt-4 pb-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('notes') border-red-300 @enderror">{{ old('notes') }}</textarea>
                    <label for="notes" class="form-label">
                        ملاحظات
                    </label>
                </div>
                @error('notes')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-end py-2 border-t gap-3 border-gray-200">
                <a href="{{ route('admin.users.index') }}"
                    class="inline-flex items-center px-4 py-1 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    إلغاء
                </a>
                <button type="submit"
                    class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    ارسال البيانات
                </button>
            </div>
        </form>
    </div>

    <!-- Info Box -->
    <div class="mt-6 bg-blue-50 border border-blue-200 rounded-md p-4">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                        clip-rule="evenodd"></path>
                </svg>
            </div>
            <div class="mr-3">
                <h3 class="text-sm font-medium text-blue-800">
                    معلومات مهمة
                </h3>
                <div class="mt-2 text-sm text-blue-700">
                    <ul class="list-disc list-inside space-y-1">
                        <li>المديرون يمكنهم الوصول لجميع أقسام النظام</li>
                        <li>العملاء يمكنهم الوصول فقط لإدارة اشتراكاتهم وأجهزتهم</li>
                        <li>سيتم إرسال بيانات الدخول للبريد الإلكتروني المحدد</li>
                        <li>يمكن تعديل صلاحيات المستخدم لاحقاً</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Form validation
    document.querySelector('form').addEventListener('submit', function(e) {
        const password = document.getElementById('password').value;
        const confirmation = document.getElementById('password_confirmation').value;

        if (password !== confirmation) {
            e.preventDefault();
            showMessage('كلمة المرور وتأكيدها غير متطابقتان', 'error');
            return false;
        }

        if (password.length < 8) {
            e.preventDefault();
            showMessage('كلمة المرور يجب أن تتكون من 8 أحرف على الأقل', 'error');
            return false;
        }
    });

    function showMessage(message, type) {
        const messageDiv = document.createElement('div');
        messageDiv.className = `fixed top-4 right-4 z-50 p-4 rounded-md ${type === 'success' ? 'bg-green-100 text-green-700 border border-green-400' : 'bg-red-100 text-red-700 border border-red-400'}`;
        messageDiv.textContent = message;

        document.body.appendChild(messageDiv);

        setTimeout(() => {
            messageDiv.remove();
        }, 5000);
    }
</script>
@endsection