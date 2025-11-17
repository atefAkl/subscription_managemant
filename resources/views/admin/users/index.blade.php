@extends('layouts.app')
@section('content')

<div class="max-w-7xl mx-auto py-2 sm:px-6 lg:px-8">
    <div class="bg-white rounded shadow-sm px-4 py-2 mb-0 md:flex md:items-center md:justify-between">
        <!-- Breadcrumb -->
        <x-breadcrumb :items="[
            ['title' => 'لوحة التحكم', 'url' => route('admin.dashboard')],
            ['title' => 'إدارة المسؤولين', 'url' => route('admin.users.index')],
            ['title' => 'قائمة المسؤولين']
        ]" />
    </div>
</div>

<!-- Main Content -->
<div class="max-w-7xl mx-auto py-2 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="bg-white rounded shadow-sm mb-3 px-4 py-2 sm:px-6 flex justify-between items-center">
        <div>
            <h3 class="text-lg leading-6 font-medium text-gray-900">
                قائمة المسؤولين
            </h3>
            <p class="mt-1 max-w-2xl text-sm text-gray-500">
                إدارة شاملة لجميع المسؤولين في النظام
            </p>
        </div>
        <a href="{{ route('admin.users.create') }}"
            class="btn-icon inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
            <i class="fas fa-plus"></i>
            اضافة مسؤول جديد
        </a>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">

        <div class="bg-white overflow-hidden shadow-sm rounded border-l-4 border-red-500">
            <div class="p-3">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-red-100 rounded flex items-center justify-center">
                            <i class="fas fa-user-tie text-red-600"></i>
                        </div>
                    </div>
                    <div class="mr-4 flex-1">
                        <dt class="text-sm font-medium text-gray-500">
                            المسؤولين
                        </dt>
                        <dd class="mt-1 text-3xl font-bold text-gray-900">
                            {{ $stats['total_admins'] }}
                        </dd>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow-sm rounded border-l-4 border-purple-500">
            <div class="p-3">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-purple-100 rounded flex items-center justify-center">
                            <i class="fas fa-user-plus text-purple-600"></i>
                        </div>
                    </div>
                    <div class="mr-4 flex-1">
                        <dt class="text-sm font-medium text-gray-500">
                            جدد هذا الأسبوع
                        </dt>
                        <dd class="mt-1 text-3xl font-bold text-gray-900">
                            {{ $stats['recent_admins'] }}
                        </dd>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Users Table -->
    <div class="bg-white shadow-xl overflow-hidden sm:rounded-lg">
        <div class="px-6 py-2 border-b border-gray-200">
            <h3 class="text-lg leading-6 font-medium text-gray-900">
                قائمة المسؤولين
            </h3>
            <p class="mt-1 max-w-2xl text-sm text-gray-500">
                جميع المسؤولين المسجلين في النظام مع إمكانية الإدارة الكاملة
            </p>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-right text-sm font-bold text-gray-500 uppercase tracking-wider">
                            المسؤول
                        </th>
                        <th class="px-6 py-3 text-right text-sm font-bold text-gray-500 uppercase tracking-wider">
                            البريد الإلكتروني
                        </th>
                        <th class="px-6 py-3 text-right text-sm font-bold text-gray-500 uppercase tracking-wider">
                            مستوى الوصول
                        </th>
                        <th class="px-6 py-3 text-right text-sm font-bold text-gray-500 uppercase tracking-wider">
                            تاريخ التسجيل
                        </th>
                        <th class="px-6 py-3 text-right text-sm font-bold text-gray-500 uppercase tracking-wider">
                            الإجراءات
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($users as $user)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10">
                                    <div class="h-10 w-10 rounded-full bg-gradient-to-r from-blue-500 to-purple-500 flex items-center justify-center">
                                        <span class="text-sm font-medium text-white">
                                            {{ substr($user->name, 0, 2) }}
                                        </span>
                                    </div>
                                </div>
                                <div class="mr-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                                    <div class="text-sm text-gray-500">ID: {{ $user->id }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $user->email }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        {{ $user->access_level === 1 ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">

                                <i class="fas fa-user-tie ml-1"></i> {{$user->accessLevel()}}

                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $user->created_at->format('Y-m-d') }}</div>
                            <div dir="rtl" class="text-sm text-gray-500">{{ $user->created_at->diffForHumans() }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex space-x-2 space-x-reverse">
                                <a href="{{ route('admin.users.show', $user) }}" class="btn-icon text-blue-600 hover:text-blue-900 transition-colors">
                                    <i class="fas fa-eye"></i> عرض
                                </a>
                                <a href="{{ route('admin.users.edit', $user) }}" class="btn-icon text-indigo-600 hover:text-indigo-900 transition-colors">
                                    <i class="fas fa-edit"></i> تعديل
                                </a>
                                <button onclick="showActivities({{ $user->id }})" class="btn-icon text-purple-600 hover:text-purple-900 transition-colors">
                                    <i class="fas fa-chart-line"></i> النشاطات
                                </button>
                                @if($user->id !== auth()->id())
                                <button onclick="deleteUser({{ $user->id }})" class="btn-icon text-red-600 hover:text-red-900 transition-colors">
                                    <i class="fas fa-trash"></i> حذف
                                </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center">
                            <div class="text-gray-400 text-6xl mb-4">
                                <i class="fas fa-users"></i>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">لا توجد مستخدمين</h3>
                            <p class="text-gray-500">ابدأ بإضافة أول مستخدم للنظام</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($users->hasPages())
        <div class="bg-white px-4 py-3 border-t border-gray-200">
            {{ $users->links() }}
        </div>
        @endif
    </div>
</div>

<!-- Activities Modal -->
<div id="activitiesModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full m-4 max-h-96 overflow-y-auto">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-medium text-gray-900">نشاطات المستخدم</h3>
                <button onclick="hideActivitiesModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-lg"></i>
                </button>
            </div>
        </div>

        <div id="activitiesContent" class="px-6 py-4">
            <!-- Content will be loaded here -->
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-xl max-w-md w-full m-4">
        <div class="px-6 py-4">
            <h3 class="text-lg font-medium text-gray-900 mb-4">تأكيد الحذف</h3>
            <p class="text-gray-500 mb-6">هل أنت متأكد من حذف هذا المستخدم؟ لا يمكن التراجع عن هذا الإجراء.</p>
            <div class="flex justify-end space-x-3 space-x-reverse">
                <button onclick="hideDeleteModal()" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                    إلغاء
                </button>
                <form id="deleteForm" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                        حذف
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function showActivities(userId) {
            fetch(`/admin/users/${userId}/activities`)
                .then(response => response.json())
                .then(activities => {
                    const content = activities.length > 0 
                        ? activities.map(activity => `
                            <div class="border-b border-gray-200 pb-3 mb-3">
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
                        : '<div class="text-center py-8"><div class="text-gray-400 text-4xl mb-2"><i class="fas fa-chart-line"></i></div><p class="text-gray-500">لا توجد نشاطات</p></div>';
                    
                    document.getElementById('activitiesContent').innerHTML = content;
                    document.getElementById('activitiesModal').classList.remove('hidden');
                    document.getElementById('activitiesModal').classList.add('flex');
                })
                .catch(error => {
                    console.error('Error:', error);
                    showMessage('حدث خطأ أثناء تحميل النشاطات', 'error');
                });
        }

        function hideActivitiesModal() {
            document.getElementById('activitiesModal').classList.add('hidden');
            document.getElementById('activitiesModal').classList.remove('flex');
        }

        function deleteUser(userId) {
            document.getElementById('deleteForm').action = `/admin/users/${userId}`;
            document.getElementById('deleteModal').classList.remove('hidden');
            document.getElementById('deleteModal').classList.add('flex');
        }

        function hideDeleteModal() {
            document.getElementById('deleteModal').classList.add('hidden');
            document.getElementById('deleteModal').classList.remove('flex');
        }

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