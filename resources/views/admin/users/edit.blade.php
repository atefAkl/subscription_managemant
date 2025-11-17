@extends('layouts.app')
@section('content')

<!-- Breadcrumb -->
<div class="max-w-7xl mx-auto py-2 sm:px-6 lg:px-8">
    <div class="bg-white rounded shadow-sm px-4 py-2 mb-0 md:flex md:items-center md:justify-between">
        <x-breadcrumb :items="[
            ['title' => 'لوحة التحكم', 'url' => route('admin.dashboard')],
            ['title' => 'إدارة المسؤولين', 'url' => route('admin.users.index')],
            ['title' => 'تعديل بيانات المسؤول', 'url' => '']
        ]" />
    </div>
</div>

<!-- Main Content -->
<div class="max-w-7xl mx-auto py-2 sm:px-6 lg:px-8">

    <div class="bg-white rounded shadow-sm mb-3 px-4 py-2 sm:px-6 flex justify-between items-center">
        <div>
            <h3 class="text-lg leading-6 font-medium text-gray-900">
                تعديل المسؤولين
            </h3>
            <p class="mt-1 max-w-2xl text-sm text-gray-500">
                تعديل معلومات المسؤول {{ $user->name }}
            </p>
        </div>
        <a href="{{ route('admin.users.index') }}"
            class="btn-icon inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
            <i class="fas fa-plus"></i>
            قائمة المسؤولين
        </a>
    </div> <!-- Statistics Cards -->

    <!-- Form -->
    <div class="bg-white shadow-xl overflow-hidden sm:rounded-lg">
        <div class="px-6 py-2 border-b border-gray-200">
            <h3 class="text-lg leading-6 font-medium text-gray-900">
                {{$user->name}}
            </h3>
            <p class="mt-1 max-w-2xl text-sm text-gray-500">
                يرجى تحديث المعلومات حسب الحاجة
            </p>
        </div>

        <form action="{{ route('admin.users.update', $user) }}" method="POST" class="px-6 py-6 space-y-6">
            @csrf
            @method('PUT')

            <!-- Name -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-2">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">
                        الاسم الكامل *
                    </label>
                    <div class="mt-1 relative">
                        <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}"
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
                    <label for="user_name" class="block text-sm font-medium text-gray-700">
                        اسم المستخدم *
                    </label>
                    <div class="mt-1 relative">
                        <input type="text" name="user_name" id="user_name" value="{{ old('user_name', $user->user_name) }}"
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
                        <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}"
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


            <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                <!-- Role -->
                <div>
                    <label for="role" class="block text-sm font-medium text-gray-700">
                        نوع المستخدم *
                    </label>
                    <div class="mt-1">
                        <select name="role" id="role"
                            class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('role') border-red-300 @enderror"
                            @if($user->id === auth()->id()) disabled @endif>
                            <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>مدير</option>
                            <option value="client" {{ old('role', $user->role) == 'client' ? 'selected' : '' }}>عميل</option>
                        </select>
                        @if($user->id === auth()->id())
                        <input type="hidden" name="role" value="{{ $user->role }}">
                        <p class="mt-1 text-xs text-gray-500">لا يمكن تغيير نوع حسابك الشخصي</p>
                        @endif
                    </div>
                    @error('role')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Phone -->
                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700">
                        رقم الهاتف
                    </label>
                    <div class="mt-1">
                        <input type="tel" name="phone" id="phone" value="{{ old('phone', $user->phone) }}" placeholder="مثال: 01234567890"
                            class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('phone') border-red-300 @enderror">
                    </div>
                    @error('phone')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                <!-- Address -->
                <div>
                    <div class="form-floating">

                        <textarea name="address" id="address" rows="3" placeholder=""
                            class="form-control block w-full px-3 pt-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('address') border-red-300 @enderror">{{ old('address', $user->address) }}</textarea>
                        <label for="address" class="form-label block text-sm font-medium text-gray-700">
                            العنوان
                        </label>
                    </div>
                    @error('address')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Notes -->
                <div class="">
                    <div class="form-floating">
                        <textarea name="notes" id="notes" rows="3" placeholder=""
                            class="form-control block w-full px-3 pt-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('notes') border-red-300 @enderror">{{ old('notes', $user->notes) }}</textarea>
                        <label for="notes" class="form-label block text-sm font-medium text-gray-700">
                            ملاحظات
                        </label>
                    </div>
                    @error('notes')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-end space-x-3 space-x-reverse pt-6 border-t border-gray-200">
                <a href="{{ route('admin.users.show', $user) }}"
                    class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    إلغاء
                </a>
                <button type="submit"
                    class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    حفظ التغييرات
                </button>
            </div>
        </form>
    </div>
    {{-- Change Password form --}}

    <div class="bg-white shadow-xl overflow-hidden sm:rounded-lg my-3">
        <div class="px-6 py-2 border-b border-gray-200">
            <h3 class="text-lg leading-6 font-medium text-gray-900">
                تغيير كلمة المرور
            </h3>
            <p class="mt-1 max-w-2xl text-sm text-gray-500">
                يرجى تحديث المعلومات حسب الحاجة
            </p>
        </div>
        <div class="px-6 py-4">
            <form action="">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                    <!-- Password -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700">
                            كلمة المرور الحالية
                        </label>
                        <div class="mt-1 relative">
                            <input type="password" name="password" id="password"
                                class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('password') border-red-300 @enderror">

                        </div>
                        @error('password')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror

                    </div>
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700">
                            كلمة المرور الجديدة
                        </label>
                        <div class="mt-1 relative">
                            <input type="password" name="password" id="password"
                                class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('password') border-red-300 @enderror">
                            @error('password')
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                        clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            @enderror
                        </div>
                        @error('password')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror

                    </div>

                    <!-- Password Confirmation -->
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700">
                            تأكيد كلمة المرور الجديدة
                        </label>
                        <div class="mt-1">
                            <input type="password" name="password_confirmation" id="password_confirmation"
                                class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        </div>
                    </div>
                </div>
                <!-- Action Buttons -->
                <div class="flex justify-end space-x-3 space-x-reverse pt-6 border-t border-gray-200">
                    <a href="{{ route('admin.users.show', $user) }}"
                        class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        إلغاء
                    </a>
                    <button type="submit"
                        class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        حفظ التغييرات
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Danger Zone -->
    @if($user->id !== auth()->id())
    <div class="mt-8 bg-red-50 border border-red-200 rounded-md p-6">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                        clip-rule="evenodd"></path>
                </svg>
            </div>
            <div class="mr-3">
                <h3 class="text-sm font-medium text-red-800">
                    منطقة الخطر
                </h3>
                <div class="mt-2 text-sm text-red-700">
                    <p class="mb-3">حذف هذا المستخدم سيؤدي إلى:</p>
                    <ul class="list-disc list-inside space-y-1">
                        <li>حذف جميع الاشتراكات المرتبطة به</li>
                        <li>حذف جميع الأجهزة المسجلة</li>
                        <li>حذف سجل المدفوعات</li>
                        <li>فقدان جميع البيانات المرتبطة</li>
                    </ul>
                </div>
                <div class="mt-4">
                    <button onclick="confirmDelete()"
                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700">
                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                        حذف المستخدم نهائياً
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

<!-- Delete Confirmation Modal -->
@if($user->id !== auth()->id())
<div id="deleteModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-xl max-w-md w-full m-4">
        <div class="px-6 py-4">
            <h3 class="text-lg font-medium text-gray-900 mb-4">تأكيد حذف المستخدم</h3>
            <p class="text-gray-500 mb-4">اكتب <strong>"DELETE"</strong> للتأكيد:</p>
            <input type="text" id="deleteConfirmation" placeholder="DELETE" class="w-full px-3 py-2 border border-gray-300 rounded-md mb-6">
            <div class="flex justify-end space-x-3 space-x-reverse">
                <button onclick="hideDeleteModal()" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                    إلغاء
                </button>
                <form id="deleteForm" method="POST" action="{{ route('admin.users.destroy', $user) }}" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" id="confirmDeleteBtn" disabled class="bg-red-600 hover:bg-red-700 disabled:bg-gray-400 text-white font-bold py-2 px-4 rounded">
                        تأكيد الحذف
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endif

<script>
    // Form validation
        document.querySelector('form').addEventListener('submit', function(e) {
            const password = document.getElementById('password').value;
            const confirmation = document.getElementById('password_confirmation').value;
            
            if (password && password !== confirmation) {
                e.preventDefault();
                showMessage('كلمة المرور وتأكيدها غير متطابقتان', 'error');
                return false;
            }
            
            if (password && password.length < 8) {
                e.preventDefault();
                showMessage('كلمة المرور يجب أن تتكون من 8 أحرف على الأقل', 'error');
                return false;
            }
        });

        @if($user->id !== auth()->id())
        function confirmDelete() {
            document.getElementById('deleteModal').classList.remove('hidden');
            document.getElementById('deleteModal').classList.add('flex');
        }

        function hideDeleteModal() {
            document.getElementById('deleteModal').classList.add('hidden');
            document.getElementById('deleteModal').classList.remove('flex');
            document.getElementById('deleteConfirmation').value = '';
            document.getElementById('confirmDeleteBtn').disabled = true;
        }

        // Enable delete button only when "DELETE" is typed
        document.getElementById('deleteConfirmation').addEventListener('input', function() {
            const confirmBtn = document.getElementById('confirmDeleteBtn');
            if (this.value === 'DELETE') {
                confirmBtn.disabled = false;
            } else {
                confirmBtn.disabled = true;
            }
        });
        @endif

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