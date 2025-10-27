<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>تخصيص الاشتراك - نظام إدارة الاشتراكات</title>
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font -->
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700&display=swap" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Tajawal', sans-serif;
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
                        <h1 class="text-xl font-bold text-blue-600">تخصيص الاشتراك</h1>
                    </div>
                </div>
                
                <div class="flex items-center space-x-4 space-x-reverse">
                    <a href="{{ route('client.subscription-requests.show', $subscriptionRequest->id) }}" class="text-gray-600 hover:text-blue-600 px-3 py-2 rounded-md text-sm">
                        عرض الطلب
                    </a>
                    <a href="{{ route('client.subscriptions') }}" class="text-gray-600 hover:text-blue-600 px-3 py-2 rounded-md text-sm">
                        الاشتراكات
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
            ['label' => 'الاشتراكات', 'url' => route('client.subscriptions')],
            ['label' => 'طلب #' . $subscriptionRequest->id, 'url' => route('client.subscription-requests.show', $subscriptionRequest->id)],
            ['label' => 'تخصيص الاشتراك', 'url' => '']
        ]" />

        <x-back-button :url="route('client.subscription-requests.show', $subscriptionRequest->id)" label="العودة لتفاصيل الطلب" />

        <!-- Header -->
        <div class="md:flex md:items-center md:justify-between mb-6">
            <div class="flex-1 min-w-0">
                <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                    📱 {{ $canEdit ?? true ? 'تخصيص اشتراك iPhone' : 'عرض تخصيص اشتراك iPhone' }}
                </h2>
                <p class="mt-1 text-sm text-gray-500">
                    طلب رقم #{{ $subscriptionRequest->id }} - {{ $subscriptionRequest->subscription_name }}
                    @if(!($canEdit ?? true))
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800 mr-2">
                            وضع العرض فقط
                        </span>
                    @endif
                </p>
            </div>
        </div>

        @if (session('success'))
            <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                {{ session('error') }}
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- تفاصيل الاشتراك الأساسية -->
            <div class="lg:col-span-2">
                <div class="bg-white shadow rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">
                            تفاصيل الاشتراك الأساسية
                        </h3>
                        
                        @if(!($canEdit ?? true))
                            <div class="mb-4 bg-blue-50 border border-blue-200 rounded-lg p-3">
                                <div class="flex">
                                    <svg class="h-5 w-5 text-blue-400 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                    </svg>
                                    <div class="mr-3">
                                        <p class="text-sm text-blue-700">
                                            لا يمكن تعديل هذا الاشتراك في الحالة الحالية ({{ $subscriptionRequest->status_label }}). يمكنك عرض التفاصيل فقط.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endif
                        
                        <form method="POST" action="{{ route('client.subscription-requests.customize.update', $subscriptionRequest->id) }}">
                            @csrf
                            
                            <div class="grid grid-cols-1 gap-6">
                                <div>
                                    <label for="subscription_name" class="block text-sm font-medium text-gray-700">
                                        اسم الاشتراك
                                    </label>
                                    <input type="text" name="subscription_name" id="subscription_name"
                                           value="{{ old('subscription_name', $subscriptionRequest->subscription_name) }}"
                                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 {{ !($canEdit ?? true) ? 'bg-gray-100' : '' }}"
                                           {{ !($canEdit ?? true) ? 'readonly' : 'required' }}>
                                    @error('subscription_name')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="device_count" class="block text-sm font-medium text-gray-700">
                                        عدد أجهزة iPhone المطلوبة
                                    </label>
                                    <input type="number" name="device_count" id="device_count" min="1" max="20"
                                           value="{{ old('device_count', $subscriptionRequest->device_count) }}"
                                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 {{ !($canEdit ?? true) ? 'bg-gray-100' : '' }}"
                                           {{ !($canEdit ?? true) ? 'readonly' : 'required' }}>
                                    @error('device_count')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="proposed_start_date" class="block text-sm font-medium text-gray-700">
                                        تاريخ البداية المقترح
                                    </label>
                                    <input type="date" name="proposed_start_date" id="proposed_start_date"
                                           value="{{ old('proposed_start_date', $subscriptionRequest->proposed_start_date->format('Y-m-d')) }}"
                                           min="{{ date('Y-m-d') }}"
                                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 {{ !($canEdit ?? true) ? 'bg-gray-100' : '' }}"
                                           {{ !($canEdit ?? true) ? 'readonly' : 'required' }}>
                                    @error('proposed_start_date')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="notes" class="block text-sm font-medium text-gray-700">
                                        ملاحظات إضافية
                                    </label>
                                    <textarea name="notes" id="notes" rows="3"
                                              class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 {{ !($canEdit ?? true) ? 'bg-gray-100' : '' }}"
                                              placeholder="أي متطلبات خاصة أو ملاحظات إضافية..."
                                              {{ !($canEdit ?? true) ? 'readonly' : '' }}>{{ old('notes', $subscriptionRequest->notes) }}</textarea>
                                    @error('notes')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="mt-6">
                                @if($canEdit ?? true)
                                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                        حفظ التغييرات
                                    </button>
                                @else
                                    <div class="text-sm text-gray-500">
                                        لا يمكن تعديل الاشتراك في الحالة الحالية
                                    </div>
                                @endif
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- إدارة الأجهزة -->
            <div>
                <div class="bg-white shadow rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">
                            أجهزة iPhone المطلوبة
                        </h3>
                        
                        <div class="mb-4">
                            <div class="text-sm text-gray-600">
                                <span class="font-medium" id="current-count">{{ $subscriptionRequest->requestDevices->count() }}</span>
                                من
                                <span class="font-medium">{{ $subscriptionRequest->device_count }}</span>
                                جهاز
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2 mt-2">
                                <div class="bg-blue-600 h-2 rounded-full transition-all duration-300" 
                                     id="progress-bar"
                                     style="width: {{ ($subscriptionRequest->requestDevices->count() / $subscriptionRequest->device_count) * 100 }}%"></div>
                            </div>
                        </div>

                        <!-- قائمة الأجهزة الحالية -->
                        <div id="devices-list" class="space-y-3 mb-4">
                            @foreach($subscriptionRequest->requestDevices as $device)
                            <div class="border border-gray-200 rounded-lg p-3 device-item">
                                <div class="flex items-start justify-between">
                                    <div class="flex-1">
                                        <h4 class="text-sm font-medium text-gray-900">{{ $device->device_nickname }}</h4>
                                        <p class="text-xs text-gray-500">{{ $device->iphone_model }}</p>
                                        <p class="text-xs text-blue-600 font-mono">{{ $device->device_identifier }}</p>
                                    </div>
                                    @if($canEdit ?? true)
                                        <button onclick="removeDevice({{ $device->id }})" 
                                                class="text-red-600 hover:text-red-800 text-sm">
                                            حذف
                                        </button>
                                    @endif
                                </div>
                            </div>
                            @endforeach
                        </div>

                        <!-- نموذج إضافة جهاز جديد -->
                        @if($subscriptionRequest->requestDevices->count() < $subscriptionRequest->device_count && ($canEdit ?? true))
                        <div class="border-t pt-4">
                            <button onclick="showAddDeviceModal()" 
                                    class="w-full bg-green-600 hover:bg-green-700 text-white text-sm font-medium py-2 px-3 rounded-md">
                                + إضافة جهاز iPhone
                            </button>
                        </div>
                        @elseif(!($canEdit ?? true))
                        <div class="border-t pt-4">
                            <div class="text-center text-sm text-gray-500 py-2">
                                لا يمكن إضافة أجهزة في الحالة الحالية
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Device Modal -->
    <div id="addDeviceModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full m-4">
            <div class="px-6 py-4">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-900">إضافة جهاز iPhone جديد</h3>
                    <button onclick="hideAddDeviceModal()" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <form id="addDeviceForm" onsubmit="addDevice(event)">
                    <div class="space-y-4">
                        <div>
                            <label for="device_identifier" class="block text-sm font-medium text-gray-700">
                                رقم الجهاز المميز (10 خانات)
                            </label>
                            <input type="text" id="device_identifier" name="device_identifier" maxlength="10" 
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                   placeholder="مثال: IPH1234ABC" required>
                            <p class="mt-1 text-xs text-gray-500">يجب أن يكون مكون من أحرف وأرقام فقط</p>
                        </div>

                        <div>
                            <label for="iphone_model" class="block text-sm font-medium text-gray-700">
                                طراز iPhone
                            </label>
                            <select id="iphone_model" name="iphone_model" 
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
                                <option value="">اختر طراز iPhone</option>
                                <optgroup label="iPhone 15 Series">
                                    <option value="iPhone 15 Pro Max">iPhone 15 Pro Max</option>
                                    <option value="iPhone 15 Pro">iPhone 15 Pro</option>
                                    <option value="iPhone 15 Plus">iPhone 15 Plus</option>
                                    <option value="iPhone 15">iPhone 15</option>
                                </optgroup>
                                <optgroup label="iPhone 14 Series">
                                    <option value="iPhone 14 Pro Max">iPhone 14 Pro Max</option>
                                    <option value="iPhone 14 Pro">iPhone 14 Pro</option>
                                    <option value="iPhone 14 Plus">iPhone 14 Plus</option>
                                    <option value="iPhone 14">iPhone 14</option>
                                </optgroup>
                                <optgroup label="iPhone 13 Series">
                                    <option value="iPhone 13 Pro Max">iPhone 13 Pro Max</option>
                                    <option value="iPhone 13 Pro">iPhone 13 Pro</option>
                                    <option value="iPhone 13 mini">iPhone 13 mini</option>
                                    <option value="iPhone 13">iPhone 13</option>
                                </optgroup>
                                <optgroup label="iPhone 12 Series">
                                    <option value="iPhone 12 Pro Max">iPhone 12 Pro Max</option>
                                    <option value="iPhone 12 Pro">iPhone 12 Pro</option>
                                    <option value="iPhone 12 mini">iPhone 12 mini</option>
                                    <option value="iPhone 12">iPhone 12</option>
                                </optgroup>
                                <optgroup label="iPhone 11 Series">
                                    <option value="iPhone 11 Pro Max">iPhone 11 Pro Max</option>
                                    <option value="iPhone 11 Pro">iPhone 11 Pro</option>
                                    <option value="iPhone 11">iPhone 11</option>
                                </optgroup>
                            </select>
                        </div>

                        <div>
                            <label for="device_nickname" class="block text-sm font-medium text-gray-700">
                                اسم مخصص للجهاز
                            </label>
                            <input type="text" id="device_nickname" name="device_nickname" 
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                   placeholder="مثال: آيفون المدير" required>
                        </div>

                        <div>
                            <label for="special_requirements" class="block text-sm font-medium text-gray-700">
                                متطلبات خاصة (اختياري)
                            </label>
                            <textarea id="special_requirements" name="special_requirements" rows="2"
                                      class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                      placeholder="أي متطلبات خاصة لهذا الجهاز..."></textarea>
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end space-x-3 space-x-reverse">
                        <button type="button" onclick="hideAddDeviceModal()" 
                                class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                            إلغاء
                        </button>
                        <button type="submit" 
                                class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            إضافة الجهاز
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Device identifier validation
        document.getElementById('device_identifier').addEventListener('input', function(e) {
            let value = e.target.value.toUpperCase();
            value = value.replace(/[^A-Z0-9]/g, '');
            e.target.value = value;
        });

        function showAddDeviceModal() {
            document.getElementById('addDeviceModal').classList.remove('hidden');
            document.getElementById('addDeviceModal').classList.add('flex');
        }

        function hideAddDeviceModal() {
            document.getElementById('addDeviceModal').classList.add('hidden');
            document.getElementById('addDeviceModal').classList.remove('flex');
            document.getElementById('addDeviceForm').reset();
        }

        function addDevice(event) {
            event.preventDefault();
            
            const formData = new FormData(event.target);
            
            fetch(`/client/subscription-requests/{{ $subscriptionRequest->id }}/devices/add`, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Add device to list
                    const devicesList = document.getElementById('devices-list');
                    const deviceHtml = `
                        <div class="border border-gray-200 rounded-lg p-3 device-item">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <h4 class="text-sm font-medium text-gray-900">${data.device.device_nickname}</h4>
                                    <p class="text-xs text-gray-500">${data.device.iphone_model}</p>
                                    <p class="text-xs text-blue-600 font-mono">${data.device.device_identifier}</p>
                                </div>
                                <button onclick="removeDevice(${data.device.id})" 
                                        class="text-red-600 hover:text-red-800 text-sm">
                                    حذف
                                </button>
                            </div>
                        </div>
                    `;
                    devicesList.insertAdjacentHTML('beforeend', deviceHtml);
                    
                    // Update progress
                    updateProgress();
                    
                    hideAddDeviceModal();
                    
                    // Show success message
                    showMessage(data.message, 'success');
                } else {
                    showMessage(data.message, 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showMessage('حدث خطأ أثناء إضافة الجهاز', 'error');
            });
        }

        function removeDevice(deviceId) {
            if (confirm('هل أنت متأكد من حذف هذا الجهاز؟')) {
                // Implementation for device removal
                console.log('Remove device:', deviceId);
            }
        }

        function updateProgress() {
            const currentCount = document.querySelectorAll('.device-item').length;
            const totalCount = {{ $subscriptionRequest->device_count }};
            const percentage = (currentCount / totalCount) * 100;
            
            document.getElementById('current-count').textContent = currentCount;
            document.getElementById('progress-bar').style.width = percentage + '%';
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