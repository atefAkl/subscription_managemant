<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>إدارة الأجهزة - نظام إدارة الاشتراكات</title>
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font -->
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700&display=swap" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Tajawal', sans-serif;
        }
        .accordion-content {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease-out;
        }
        .accordion-content.active {
            max-height: 500px;
            transition: max-height 0.3s ease-in;
        }
    </style>
    
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body class="bg-gray-100 min-h-screen">
    <!-- Navigation -->
    <nav class="bg-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <div class="flex-shrink-0 flex items-center">
                        <h1 class="text-xl font-bold text-blue-600">إدارة الأجهزة</h1>
                    </div>
                </div>
                
                <div class="flex items-center space-x-4 space-x-reverse">
                    <a href="{{ route('client.subscriptions') }}" class="text-gray-600 hover:text-blue-600 px-3 py-2 rounded-md text-sm">
                        الاشتراكات
                    </a>
                    <a href="{{ route('client.dashboard') }}" class="text-gray-600 hover:text-blue-600 px-3 py-2 rounded-md text-sm">
                        لوحة التحكم
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
    </nav>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <!-- Breadcrumb & Back Button -->
        <x-breadcrumb :items="[
            ['label' => 'إدارة الأجهزة', 'url' => '']
        ]" />

        <x-back-button :url="route('client.dashboard')" label="العودة للوحة التحكم" />

        <!-- Header -->
        <div class="md:flex md:items-center md:justify-between mb-6">
            <div class="flex-1 min-w-0">
                <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                    📱 إدارة أجهزة iPhone
                </h2>
                <p class="mt-1 text-sm text-gray-500">
                    جميع أجهزة iPhone المسجلة في اشتراكاتك
                </p>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
            @php
                $totalDevices = $subscriptions->sum(fn($sub) => $sub->devices->count());
                $activeDevices = $subscriptions->sum(fn($sub) => $sub->devices->where('status', 'active')->count());
                $pendingDevices = $subscriptions->sum(fn($sub) => $sub->devices->where('status', 'pending')->count());
                $blockedDevices = $subscriptions->sum(fn($sub) => $sub->devices->where('status', 'blocked')->count());
            @endphp

            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-blue-500 rounded-md flex items-center justify-center">
                                <span class="text-white text-sm font-bold">📱</span>
                            </div>
                        </div>
                        <div class="mr-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">
                                    إجمالي الأجهزة
                                </dt>
                                <dd class="text-lg font-medium text-gray-900">
                                    {{ $totalDevices }}
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-green-500 rounded-md flex items-center justify-center">
                                <span class="text-white text-sm font-bold">✅</span>
                            </div>
                        </div>
                        <div class="mr-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">
                                    أجهزة نشطة
                                </dt>
                                <dd class="text-lg font-medium text-gray-900">
                                    {{ $activeDevices }}
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-yellow-500 rounded-md flex items-center justify-center">
                                <span class="text-white text-sm font-bold">⏳</span>
                            </div>
                        </div>
                        <div class="mr-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">
                                    في انتظار التفعيل
                                </dt>
                                <dd class="text-lg font-medium text-gray-900">
                                    {{ $pendingDevices }}
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-red-500 rounded-md flex items-center justify-center">
                                <span class="text-white text-sm font-bold">🚫</span>
                            </div>
                        </div>
                        <div class="mr-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">
                                    محظورة
                                </dt>
                                <dd class="text-lg font-medium text-gray-900">
                                    {{ $blockedDevices }}
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Devices Accordion -->
        <div class="bg-white shadow overflow-hidden sm:rounded-md">
            <div class="px-4 py-5 sm:px-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900">
                    قائمة الأجهزة حسب الاشتراك
                </h3>
                <p class="mt-1 max-w-2xl text-sm text-gray-500">
                    انقر على أي اشتراك لعرض أجهزته
                </p>
            </div>

            @forelse($subscriptions as $subscription)
                <div class="border-t border-gray-200">
                    <!-- Subscription Header -->
                    <button onclick="toggleAccordion('subscription-{{ $subscription->id }}')" 
                            class="w-full px-4 py-4 sm:px-6 text-right bg-gray-50 hover:bg-gray-100 focus:outline-none focus:bg-gray-100">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                        <span class="text-blue-600 font-semibold">{{ $subscription->devices->count() }}</span>
                                    </div>
                                </div>
                                <div class="mr-3 text-right">
                                    <h4 class="text-sm font-medium text-gray-900">
                                        {{ $subscription->name }}
                                    </h4>
                                    <p class="text-sm text-gray-500">
                                        {{ $subscription->devices->count() }} جهاز • 
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium 
                                            @if($subscription->status == 'active') bg-green-100 text-green-800
                                            @else bg-gray-100 text-gray-800 @endif">
                                            {{ $subscription->status == 'active' ? 'نشط' : $subscription->status }}
                                        </span>
                                    </p>
                                </div>
                            </div>
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-gray-400 accordion-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </div>
                        </div>
                    </button>

                    <!-- Devices List (Accordion Content) -->
                    <div id="subscription-{{ $subscription->id }}" class="accordion-content">
                        <div class="px-4 py-3 sm:px-6 bg-white">
                            @forelse($subscription->devices as $device)
                                <div class="border border-gray-200 rounded-lg mb-3 p-4 device-item" data-device-id="{{ $device->id }}">
                                    <div class="flex items-start justify-between">
                                        <div class="flex items-start flex-1">
                                            <div class="flex-shrink-0">
                                                <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-purple-600 rounded-lg flex items-center justify-center">
                                                    <span class="text-white text-lg">📱</span>
                                                </div>
                                            </div>
                                            <div class="mr-3 flex-1">
                                                <div class="flex items-center justify-between">
                                                    <div>
                                                        <h5 class="text-sm font-medium text-gray-900 device-name">
                                                            {{ $device->device_nickname ?: $device->device_name ?: 'جهاز غير مسمى' }}
                                                        </h5>
                                                        <p class="text-xs text-gray-500">{{ $device->iphone_model }}</p>
                                                        <p class="text-xs text-blue-600 font-mono mt-1">{{ $device->device_identifier }}</p>
                                                    </div>
                                                    <div class="text-left">
                                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium 
                                                            @if($device->status == 'active') bg-green-100 text-green-800
                                                            @elseif($device->status == 'pending') bg-yellow-100 text-yellow-800
                                                            @elseif($device->status == 'blocked') bg-red-100 text-red-800
                                                            @else bg-gray-100 text-gray-800 @endif">
                                                            @if($device->status == 'active') نشط
                                                            @elseif($device->status == 'pending') في انتظار التفعيل
                                                            @elseif($device->status == 'blocked') محظور
                                                            @else {{ $device->status }} @endif
                                                        </span>
                                                    </div>
                                                </div>
                                                
                                                <!-- Device Details -->
                                                <div class="mt-3 grid grid-cols-1 sm:grid-cols-3 gap-4 text-xs">
                                                    <div>
                                                        <span class="font-medium text-gray-500">آخر اتصال:</span>
                                                        <span class="text-gray-900">
                                                            {{ $device->last_connected_at ? $device->last_connected_at->diffForHumans() : 'لم يتصل بعد' }}
                                                        </span>
                                                    </div>
                                                    @if($device->ip_address)
                                                    <div>
                                                        <span class="font-medium text-gray-500">عنوان IP:</span>
                                                        <span class="text-gray-900 font-mono">{{ $device->ip_address }}</span>
                                                    </div>
                                                    @endif
                                                    @if($device->activated_at)
                                                    <div>
                                                        <span class="font-medium text-gray-500">تاريخ التفعيل:</span>
                                                        <span class="text-gray-900">{{ $device->activated_at->format('Y-m-d') }}</span>
                                                    </div>
                                                    @endif
                                                </div>

                                                <!-- Action Buttons -->
                                                <div class="mt-4 flex space-x-2 space-x-reverse">
                                                    <button onclick="editDeviceName({{ $device->id }}, '{{ addslashes($device->device_nickname ?: $device->device_name) }}')" 
                                                            class="inline-flex items-center px-3 py-1 border border-gray-300 shadow-sm text-xs font-medium rounded text-gray-700 bg-white hover:bg-gray-50">
                                                        ✏️ تعديل الاسم
                                                    </button>
                                                    
                                                    @if($device->status == 'pending')
                                                        <button onclick="deleteDevice({{ $device->id }})" 
                                                                class="inline-flex items-center px-3 py-1 border border-red-300 shadow-sm text-xs font-medium rounded text-red-700 bg-red-50 hover:bg-red-100">
                                                            🗑️ حذف
                                                        </button>
                                                    @endif

                                                    @if($device->token)
                                                        <button onclick="copyToken('{{ $device->token }}')" 
                                                                class="inline-flex items-center px-3 py-1 border border-blue-300 shadow-sm text-xs font-medium rounded text-blue-700 bg-blue-50 hover:bg-blue-100">
                                                            📋 نسخ الرمز
                                                        </button>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-6">
                                    <div class="text-gray-400 text-4xl mb-2">📱</div>
                                    <p class="text-gray-500">لا توجد أجهزة مسجلة في هذا الاشتراك</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-12">
                    <div class="text-gray-400 text-6xl mb-4">📱</div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">لا توجد أجهزة</h3>
                    <p class="text-gray-500 mb-4">لم تقم بتسجيل أي أجهزة بعد</p>
                    <a href="{{ route('client.subscriptions.create') }}" 
                       class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                        طلب اشتراك جديد
                    </a>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Edit Device Name Modal -->
    <div id="editNameModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full m-4">
            <div class="px-6 py-4">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-900">تعديل اسم الجهاز</h3>
                    <button onclick="hideEditNameModal()" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <form id="editNameForm" onsubmit="updateDeviceName(event)">
                    <div class="mb-4">
                        <label for="new_device_name" class="block text-sm font-medium text-gray-700 mb-2">
                            الاسم الجديد
                        </label>
                        <input type="text" id="new_device_name" name="device_nickname" 
                               class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                               required>
                    </div>
                    
                    <div class="flex justify-end space-x-3 space-x-reverse">
                        <button type="button" onclick="hideEditNameModal()" 
                                class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                            إلغاء
                        </button>
                        <button type="submit" 
                                class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            حفظ
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        let currentDeviceId = null;

        function toggleAccordion(id) {
            const content = document.getElementById(id);
            const icon = content.previousElementSibling.querySelector('.accordion-icon');
            
            if (content.classList.contains('active')) {
                content.classList.remove('active');
                icon.style.transform = 'rotate(0deg)';
            } else {
                // Close all other accordions
                document.querySelectorAll('.accordion-content.active').forEach(el => {
                    el.classList.remove('active');
                    el.parentElement.querySelector('.accordion-icon').style.transform = 'rotate(0deg)';
                });
                
                content.classList.add('active');
                icon.style.transform = 'rotate(180deg)';
            }
        }

        function editDeviceName(deviceId, currentName) {
            currentDeviceId = deviceId;
            document.getElementById('new_device_name').value = currentName;
            document.getElementById('editNameModal').classList.remove('hidden');
            document.getElementById('editNameModal').classList.add('flex');
        }

        function hideEditNameModal() {
            document.getElementById('editNameModal').classList.add('hidden');
            document.getElementById('editNameModal').classList.remove('flex');
            currentDeviceId = null;
        }

        function updateDeviceName(event) {
            event.preventDefault();
            
            const formData = new FormData(event.target);
            
            fetch(`/client/devices/${currentDeviceId}/update-name`, {
                method: 'PUT',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update device name in the UI
                    const deviceElement = document.querySelector(`[data-device-id="${currentDeviceId}"] .device-name`);
                    deviceElement.textContent = data.new_name;
                    
                    hideEditNameModal();
                    showMessage(data.message, 'success');
                } else {
                    showMessage(data.message, 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showMessage('حدث خطأ أثناء تحديث اسم الجهاز', 'error');
            });
        }

        function deleteDevice(deviceId) {
            if (confirm('هل أنت متأكد من حذف هذا الجهاز؟ لا يمكن التراجع عن هذا الإجراء.')) {
                fetch(`/client/devices/${deviceId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Remove device from UI
                        const deviceElement = document.querySelector(`[data-device-id="${deviceId}"]`);
                        deviceElement.remove();
                        
                        showMessage(data.message, 'success');
                        
                        // Refresh page to update statistics
                        setTimeout(() => {
                            location.reload();
                        }, 1500);
                    } else {
                        showMessage(data.message, 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showMessage('حدث خطأ أثناء حذف الجهاز', 'error');
                });
            }
        }

        function copyToken(token) {
            navigator.clipboard.writeText(token).then(() => {
                showMessage('تم نسخ رمز التفعيل بنجاح', 'success');
            }).catch(() => {
                showMessage('فشل في نسخ رمز التفعيل', 'error');
            });
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
</body>
</html>