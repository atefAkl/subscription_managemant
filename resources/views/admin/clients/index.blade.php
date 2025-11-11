@extends('layouts.app')
@section('content')
<x-breadcrumb :items="[
    ['label' => 'لوحة التحكم', 'url' => route('admin.dashboard')],
    ['label' => 'إدارة العملاء', 'url' => route('admin.clients.index')],
]" />
<!-- Statistics Cards -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <!-- Total Clients -->
    <div class="bg-white overflow-hidden shadow-lg rounded-lg border-t-4 border-blue-500">
        <div class="p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-users text-blue-600"></i>
                    </div>
                </div>
                <div class="mr-4 flex-1">
                    <dt class="text-sm font-medium text-gray-500">
                        إجمالي العملاء
                    </dt>
                    <dd class="mt-1 text-3xl font-bold text-gray-900">
                        {{ $stats['total_clients'] }}
                    </dd>
                </div>
            </div>
        </div>
    </div>

    <!-- Active Clients -->
    <div class="bg-white overflow-hidden shadow-lg rounded-lg border-t-4 border-green-500">
        <div class="p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-user-check text-green-600"></i>
                    </div>
                </div>
                <div class="mr-4 flex-1">
                    <dt class="text-sm font-medium text-gray-500">
                        العملاء النشطين
                    </dt>
                    <dd class="mt-1 text-3xl font-bold text-gray-900">
                        {{ $stats['active_clients'] }}
                    </dd>
                </div>
            </div>
        </div>
    </div>

    <!-- New Clients -->
    <div class="bg-white overflow-hidden shadow-lg rounded-lg border-t-4 border-purple-500">
        <div class="p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-user-plus text-purple-600"></i>
                    </div>
                </div>
                <div class="mr-4 flex-1">
                    <dt class="text-sm font-medium text-gray-500">
                        عملاء جدد (30 يوم)
                    </dt>
                    <dd class="mt-1 text-3xl font-bold text-gray-900">
                        {{ $stats['new_clients'] }}
                    </dd>
                </div>
            </div>
        </div>
    </div>

    <!-- Expiring Soon -->
    <div class="bg-white overflow-hidden shadow-lg rounded-lg border-t-4 border-orange-500">
        <div class="p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-exclamation-triangle text-orange-600"></i>
                    </div>
                </div>
                <div class="mr-4 flex-1">
                    <dt class="text-sm font-medium text-gray-500">
                        ينتهي قريباً (7 أيام)
                    </dt>
                    <dd class="mt-1 text-3xl font-bold text-gray-900">
                        {{ $stats['expiring_soon'] }}
                    </dd>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Filters & Search -->
<div class="bg-white shadow-lg rounded-lg mb-6">
    <div class="p-6">
        <form method="GET" action="{{ route('admin.clients.index') }}" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                <!-- Search -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">البحث</label>
                    <input type="text" name="search" value="{{ request('search') }}" 
                            placeholder="اسم العميل أو البريد الإلكتروني..."
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <!-- Subscription Status -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">حالة الاشتراك</label>
                    <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">الكل</option>
                        <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>نشط</option>
                        <option value="trial" {{ request('status') === 'trial' ? 'selected' : '' }}>تجريبي</option>
                        <option value="expired" {{ request('status') === 'expired' ? 'selected' : '' }}>منتهي</option>
                        <option value="suspended" {{ request('status') === 'suspended' ? 'selected' : '' }}>معلق</option>
                    </select>
                </div>

                <!-- Subscription Type -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">نوع الاشتراك</label>
                    <select name="type" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">الكل</option>
                        <option value="basic" {{ request('type') === 'basic' ? 'selected' : '' }}>أساسي</option>
                        <option value="premium" {{ request('type') === 'premium' ? 'selected' : '' }}>مميز</option>
                        <option value="enterprise" {{ request('type') === 'enterprise' ? 'selected' : '' }}>مؤسسي</option>
                    </select>
                </div>

                <!-- Payment Status -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">حالة الدفع</label>
                    <select name="payment" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">الكل</option>
                        <option value="paid" {{ request('payment') === 'paid' ? 'selected' : '' }}>مدفوع</option>
                        <option value="pending" {{ request('payment') === 'pending' ? 'selected' : '' }}>معلق</option>
                        <option value="overdue" {{ request('payment') === 'overdue' ? 'selected' : '' }}>متأخر</option>
                    </select>
                </div>

                <!-- Actions -->
                <div class="flex items-end space-x-2 space-x-reverse">
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                        <i class="fas fa-search ml-1"></i>
                        بحث
                    </button>
                    <a href="{{ route('admin.clients.index') }}" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 transition-colors">
                        <i class="fas fa-times ml-1"></i>
                        مسح
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Clients Table -->
<div class="bg-white shadow-lg overflow-hidden sm:rounded-lg">
    <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
        <div>
            <h3 class="text-lg leading-6 font-medium text-gray-900">
                <i class="fas fa-users ml-1"></i>
                قائمة العملاء
            </h3>
            <p class="mt-1 max-w-2xl text-sm text-gray-500">
                إدارة جميع عملاء النظام واشتراكاتهم
            </p>
        </div>
        {{-- <div>
            <a href="{{ route('admin.clients.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded transition-colors">
                <i class="fas fa-plus ml-1"></i>
                إضافة عميل جديد
            </a>
        </div> --}}
    </div>
    
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                        العميل
                    </th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                        نوع الاشتراك
                    </th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                        حالة الاشتراك
                    </th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                        الأجهزة
                    </th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                        حالة الدفع
                    </th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                        تاريخ الانتهاء
                    </th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                        الإجراءات
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($clients as $client)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10">
                                    <div class="h-10 w-10 rounded-full bg-gradient-to-r from-blue-500 to-green-500 flex items-center justify-center">
                                        <span class="text-sm font-medium text-white">
                                            {{ substr($client->name, 0, 2) }}
                                        </span>
                                    </div>
                                </div>
                                <div class="mr-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $client->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $client->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                @if($client->clientProfile?->subscription_type === 'basic') bg-gray-100 text-gray-800
                                @elseif($client->clientProfile?->subscription_type === 'premium') bg-blue-100 text-blue-800
                                @elseif($client->clientProfile?->subscription_type === 'enterprise') bg-purple-100 text-purple-800
                                @else bg-gray-100 text-gray-800 @endif">
                                {{ $client->clientProfile?->getSubscriptionTypeText() ?? 'غير محدد' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                @if($client->clientProfile?->subscription_status === 'active') bg-green-100 text-green-800
                                @elseif($client->clientProfile?->subscription_status === 'trial') bg-yellow-100 text-yellow-800
                                @elseif($client->clientProfile?->subscription_status === 'expired') bg-red-100 text-red-800
                                @elseif($client->clientProfile?->subscription_status === 'suspended') bg-orange-100 text-orange-800
                                @else bg-gray-100 text-gray-800 @endif">
                                {{ $client->clientProfile?->getSubscriptionStatusText() ?? 'غير محدد' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            <div class="flex items-center">
                                <i class="fas fa-mobile-alt text-gray-400 ml-1"></i>
                                {{ $client->clientProfile?->devices_count ?? 0 }} / {{ $client->clientProfile?->device_limit ?? 0 }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                @if($client->clientProfile?->payment_status === 'paid') bg-green-100 text-green-800
                                @elseif($client->clientProfile?->payment_status === 'pending') bg-yellow-100 text-yellow-800
                                @elseif($client->clientProfile?->payment_status === 'overdue') bg-red-100 text-red-800
                                @else bg-gray-100 text-gray-800 @endif">
                                {{ $client->clientProfile?->getPaymentStatusText() ?? 'غير محدد' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            @if($client->clientProfile?->subscription_end_date)
                                <div class="flex flex-col">
                                    <span>{{ $client->clientProfile->subscription_end_date->format('Y-m-d') }}</span>
                                    <span class="text-xs text-gray-500">
                                        @php
                                            $remaining = $client->clientProfile->getRemainingDays();
                                        @endphp
                                        @if($remaining !== null)
                                            @if($remaining > 0)
                                                باقي {{ $remaining }} يوم
                                            @elseif($remaining == 0)
                                                ينتهي اليوم
                                            @else
                                                انتهى منذ {{ abs($remaining) }} يوم
                                            @endif
                                        @endif
                                    </span>
                                </div>
                            @else
                                <span class="text-gray-500">غير محدد</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex items-center space-x-2 space-x-reverse">
                                <a href="{{ route('admin.clients.show', $client) }}" class="text-blue-600 hover:text-blue-900" title="عرض التفاصيل">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.clients.edit', $client) }}" class="text-green-600 hover:text-green-900" title="تعديل">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button onclick="deleteClient({{ $client->id }})" class="text-red-600 hover:text-red-900" title="حذف">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-8 text-center">
                            <div class="text-gray-400 text-6xl mb-4">
                                <i class="fas fa-users"></i>
                            </div>
                            <p class="text-gray-500">لا يوجد عملاء حتى الآن</p>
                            <a href="{{ route('admin.clients.create') }}" class="mt-4 inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:outline-none focus:border-blue-700 focus:ring focus:ring-blue-200 active:bg-blue-600 disabled:opacity-25 transition">
                                <i class="fas fa-plus ml-1"></i>
                                إضافة عميل جديد
                            </a>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($clients->hasPages())
        <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
            {{ $clients->links() }}
        </div>
    @endif
</div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
<div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
    <div class="mt-3 text-center">
        <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
            <i class="fas fa-exclamation-triangle text-red-600"></i>
        </div>
        <h3 class="text-lg font-medium text-gray-900 mt-2">تأكيد الحذف</h3>
        <div class="mt-2 px-7 py-3">
            <p class="text-sm text-gray-500">
                هل أنت متأكد من حذف هذا العميل؟ هذا الإجراء لا يمكن التراجع عنه.
            </p>
        </div>
        <div class="items-center px-4 py-3">
            <form id="deleteForm" method="POST" class="inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-4 py-2 bg-red-500 text-white text-base font-medium rounded-md w-24 mr-2 hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-300">
                    حذف
                </button>
            </form>
            <button onclick="closeDeleteModal()" class="px-4 py-2 bg-gray-500 text-white text-base font-medium rounded-md w-24 hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-300">
                إلغاء
            </button>
        </div>
    </div>
</div>
</div>

<script>
function deleteClient(clientId) {
    const modal = document.getElementById('deleteModal');
    const form = document.getElementById('deleteForm');
    form.action = `/admin/clients/${clientId}`;
    modal.classList.remove('hidden');
}

function closeDeleteModal() {
    const modal = document.getElementById('deleteModal');
    modal.classList.add('hidden');
}

// Close modal when clicking outside
document.getElementById('deleteModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeDeleteModal();
    }
});
</script>
@endsection