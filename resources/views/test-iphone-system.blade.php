@extends('layouts.app')

@section('title', 'اختبار نظام iPhone')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- عنوان الصفحة -->
        <div class="text-center mb-12">
            <h1 class="text-4xl font-bold text-gray-900 mb-4">
                🍎 نظام إدارة اشتراكات iPhone
            </h1>
            <p class="text-xl text-gray-600">
                نظام متخصص لإدارة أجهزة iPhone حصرياً
            </p>
        </div>

        <!-- إحصائيات سريعة -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-12">
            <div class="bg-white rounded-xl shadow-md p-6 text-center">
                <div class="text-3xl mb-2">📱</div>
                <div class="text-2xl font-bold text-blue-600">{{ \App\Models\Device::count() }}</div>
                <div class="text-gray-600">إجمالي الأجهزة</div>
            </div>
            
            <div class="bg-white rounded-xl shadow-md p-6 text-center">
                <div class="text-3xl mb-2">✅</div>
                <div class="text-2xl font-bold text-green-600">{{ \App\Models\Device::where('status', 'active')->count() }}</div>
                <div class="text-gray-600">أجهزة نشطة</div>
            </div>
            
            <div class="bg-white rounded-xl shadow-md p-6 text-center">
                <div class="text-3xl mb-2">⏳</div>
                <div class="text-2xl font-bold text-yellow-600">{{ \App\Models\Device::where('status', 'pending')->count() }}</div>
                <div class="text-gray-600">في انتظار التفعيل</div>
            </div>
            
            <div class="bg-white rounded-xl shadow-md p-6 text-center">
                <div class="text-3xl mb-2">👥</div>
                <div class="text-2xl font-bold text-purple-600">{{ \App\Models\User::where('role', 'client')->count() }}</div>
                <div class="text-gray-600">العملاء</div>
            </div>
        </div>

        <!-- جدول الأجهزة -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-8">
            <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">أجهزة iPhone المسجلة</h3>
            </div>
            
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                الجهاز
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                الطراز
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                الرقم المميز
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                الحالة
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                آخر اتصال
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse(\App\Models\Device::with('subscription.user')->get() as $device)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="text-2xl ml-3">📱</div>
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $device->device_nickname ?? $device->device_name ?? 'غير محدد' }}
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            {{ $device->subscription->user->name ?? 'غير محدد' }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $device->iphone_model ?? 'غير محدد' }}</div>
                                <div class="text-sm text-gray-500">{{ $device->device_version ?? '' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                    {{ $device->device_identifier ?? 'غير محدد' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($device->status === 'active')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        نشط
                                    </span>
                                @elseif($device->status === 'pending')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                        في انتظار التفعيل
                                    </span>
                                @else
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                        {{ $device->status }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $device->last_connected_at ? $device->last_connected_at->diffForHumans() : 'لم يتصل بعد' }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                                لا توجد أجهزة مسجلة
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- معلومات الحسابات التجريبية -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">🔑 حسابات الاختبار</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="border border-gray-200 rounded-lg p-4">
                    <h4 class="font-semibold text-gray-900 mb-2">👨‍💼 حساب المدير</h4>
                    <p class="text-sm text-gray-600 mb-2">
                        <strong>البريد الإلكتروني:</strong> manager@test.com
                    </p>
                    <p class="text-sm text-gray-600 mb-3">
                        <strong>كلمة المرور:</strong> password
                    </p>
                    <a href="{{ route('login') }}" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                        تسجيل الدخول كمدير
                    </a>
                </div>
                
                <div class="border border-gray-200 rounded-lg p-4">
                    <h4 class="font-semibold text-gray-900 mb-2">👤 حساب العميل</h4>
                    <p class="text-sm text-gray-600 mb-2">
                        <strong>البريد الإلكتروني:</strong> client@test.com
                    </p>
                    <p class="text-sm text-gray-600 mb-3">
                        <strong>كلمة المرور:</strong> password
                    </p>
                    <a href="{{ route('login') }}" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-green-600 hover:bg-green-700">
                        تسجيل الدخول كعميل
                    </a>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection