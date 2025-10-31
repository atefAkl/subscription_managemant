@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.clients.index') }}">إدارة العملاء</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.clients.show', $client) }}">{{ $client->name }}</a></li>
                    <li class="breadcrumb-item active">تعديل</li>
                </ol>
            </nav>
            <h2 class="mb-1">تعديل بيانات العميل: {{ $client->name }}</h2>
            <p class="text-muted mb-0">تحديث معلومات العميل والاشتراكات</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.clients.show', $client) }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left"></i> العودة لتفاصيل العميل
            </a>
            <a href="{{ route('admin.clients.index') }}" class="btn btn-outline-primary">
                <i class="fas fa-list"></i> قائمة العملاء
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <!-- Client Information Form -->
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-user me-2"></i>
                        معلومات العميل الأساسية
                    </h5>
                </div>
                <form action="{{ route('admin.clients.update', $client) }}" method="POST" id="clientForm">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">الاسم الكامل *</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       id="name" name="name" value="{{ old('name', $client->name) }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">البريد الإلكتروني *</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                       id="email" name="email" value="{{ old('email', $client->email) }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="phone" class="form-label">رقم الهاتف</label>
                                <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                                       id="phone" name="phone" value="{{ old('phone', $client->phone) }}">
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="status" class="form-label">حالة العميل</label>
                                <select class="form-select @error('status') is-invalid @enderror" 
                                        id="status" name="status">
                                    <option value="active" {{ old('status', $client->is_active ?? true) ? 'selected' : '' }}>نشط</option>
                                    <option value="inactive" {{ !old('status', $client->is_active ?? true) ? 'selected' : '' }}>غير نشط</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12 mb-3">
                                <label for="address" class="form-label">العنوان</label>
                                <textarea class="form-control @error('address') is-invalid @enderror" 
                                          id="address" name="address" rows="2">{{ old('address', $client->address ?? '') }}</textarea>
                                @error('address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12 mb-3">
                                <label for="notes" class="form-label">ملاحظات إدارية</label>
                                <textarea class="form-control @error('notes') is-invalid @enderror" 
                                          id="notes" name="notes" rows="3">{{ old('notes', $client->notes ?? '') }}</textarea>
                                @error('notes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>
                            حفظ التغييرات
                        </button>
                        <button type="button" class="btn btn-secondary ms-2" onclick="resetForm()">
                            <i class="fas fa-undo me-2"></i>
                            إعادة تعيين
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Client Profile & Subscription -->
        <div class="col-lg-4">
            <!-- Client Summary -->
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="fas fa-info-circle me-2"></i>
                        ملخص العميل
                    </h6>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center" 
                             style="width: 60px; height: 60px; font-size: 18px; font-weight: bold;">
                            {{ substr($client->name, 0, 2) }}
                        </div>
                    </div>

                    <div class="small text-muted mb-2">
                        <i class="fas fa-calendar me-1"></i>
                        تاريخ التسجيل: {{ $client->created_at->format('Y/m/d') }}
                    </div>
                    
                    @if($client->email_verified_at)
                        <div class="small text-success mb-2">
                            <i class="fas fa-check-circle me-1"></i>
                            البريد الإلكتروني مؤكد
                        </div>
                    @else
                        <div class="small text-warning mb-2">
                            <i class="fas fa-exclamation-circle me-1"></i>
                            البريد الإلكتروني غير مؤكد
                        </div>
                    @endif

                    <div class="small text-muted">
                        <i class="fas fa-clock me-1"></i>
                        آخر تحديث: {{ $client->updated_at->diffForHumans() }}
                    </div>
                </div>
            </div>

            <!-- Subscription Management -->
            @if($client->clientProfile)
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">
                        <i class="fas fa-crown me-2"></i>
                        إدارة الاشتراك
                    </h6>
                    <span class="badge {{ $client->clientProfile->subscription_status === 'active' ? 'bg-success' : 'bg-warning' }}">
                        {{ $client->clientProfile->getSubscriptionStatusText() }}
                    </span>
                </div>
                <form action="{{ route('admin.clients.update-subscription', $client) }}" method="POST" id="subscriptionForm">
                    @csrf
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="subscription_type" class="form-label">نوع الاشتراك</label>
                            <select class="form-select" id="subscription_type" name="subscription_type">
                                <option value="basic" {{ $client->clientProfile->subscription_type === 'basic' ? 'selected' : '' }}>أساسي</option>
                                <option value="premium" {{ $client->clientProfile->subscription_type === 'premium' ? 'selected' : '' }}>مميز</option>
                                <option value="enterprise" {{ $client->clientProfile->subscription_type === 'enterprise' ? 'selected' : '' }}>مؤسسي</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="subscription_status" class="form-label">حالة الاشتراك</label>
                            <select class="form-select" id="subscription_status" name="subscription_status">
                                <option value="active" {{ $client->clientProfile->subscription_status === 'active' ? 'selected' : '' }}>نشط</option>
                                <option value="trial" {{ $client->clientProfile->subscription_status === 'trial' ? 'selected' : '' }}>تجريبي</option>
                                <option value="expired" {{ $client->clientProfile->subscription_status === 'expired' ? 'selected' : '' }}>منتهي</option>
                                <option value="suspended" {{ $client->clientProfile->subscription_status === 'suspended' ? 'selected' : '' }}>معلق</option>
                            </select>
                        </div>

                        <div class="row">
                            <div class="col-6 mb-3">
                                <label for="subscription_start_date" class="form-label">تاريخ البداية</label>
                                <input type="date" class="form-control" id="subscription_start_date" 
                                       name="subscription_start_date" 
                                       value="{{ $client->clientProfile->subscription_start_date?->format('Y-m-d') }}">
                            </div>
                            <div class="col-6 mb-3">
                                <label for="subscription_end_date" class="form-label">تاريخ الانتهاء</label>
                                <input type="date" class="form-control" id="subscription_end_date" 
                                       name="subscription_end_date" 
                                       value="{{ $client->clientProfile->subscription_end_date?->format('Y-m-d') }}">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-6 mb-3">
                                <label for="device_limit" class="form-label">حد الأجهزة</label>
                                <input type="number" class="form-control" id="device_limit" 
                                       name="device_limit" min="1" max="50"
                                       value="{{ $client->clientProfile->device_limit ?? 5 }}">
                            </div>
                            <div class="col-6 mb-3">
                                <label for="billing_cycle" class="form-label">دورة الفوترة</label>
                                <select class="form-select" id="billing_cycle" name="billing_cycle">
                                    <option value="monthly" {{ $client->clientProfile->billing_cycle === 'monthly' ? 'selected' : '' }}>شهرياً</option>
                                    <option value="quarterly" {{ $client->clientProfile->billing_cycle === 'quarterly' ? 'selected' : '' }}>كل 3 أشهر</option>
                                    <option value="semi_annually" {{ $client->clientProfile->billing_cycle === 'semi_annually' ? 'selected' : '' }}>كل 6 أشهر</option>
                                    <option value="annually" {{ $client->clientProfile->billing_cycle === 'annually' ? 'selected' : '' }}>سنوياً</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="payment_status" class="form-label">حالة الدفع</label>
                            <select class="form-select" id="payment_status" name="payment_status">
                                <option value="paid" {{ $client->clientProfile->payment_status === 'paid' ? 'selected' : '' }}>مدفوع</option>
                                <option value="pending" {{ $client->clientProfile->payment_status === 'pending' ? 'selected' : '' }}>في الانتظار</option>
                                <option value="overdue" {{ $client->clientProfile->payment_status === 'overdue' ? 'selected' : '' }}>متأخر</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="client_notes" class="form-label">ملاحظات الاشتراك</label>
                            <textarea class="form-control" id="client_notes" name="client_notes" rows="3">{{ $client->clientProfile->client_notes }}</textarea>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-success btn-sm">
                            <i class="fas fa-save me-1"></i>
                            تحديث الاشتراك
                        </button>
                    </div>
                </form>
            </div>
            @else
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="fas fa-plus-circle me-2"></i>
                        إنشاء اشتراك جديد
                    </h6>
                </div>
                <form action="{{ route('admin.clients.create-subscription', $client) }}" method="POST">
                    @csrf
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="new_subscription_type" class="form-label">نوع الاشتراك</label>
                            <select class="form-select" id="new_subscription_type" name="subscription_type" required>
                                <option value="">اختر نوع الاشتراك</option>
                                <option value="basic">أساسي</option>
                                <option value="premium">مميز</option>
                                <option value="enterprise">مؤسسي</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="new_device_limit" class="form-label">حد الأجهزة</label>
                            <input type="number" class="form-control" id="new_device_limit" 
                                   name="device_limit" min="1" max="50" value="5" required>
                        </div>

                        <div class="mb-3">
                            <label for="new_billing_cycle" class="form-label">دورة الفوترة</label>
                            <select class="form-select" id="new_billing_cycle" name="billing_cycle" required>
                                <option value="monthly">شهرياً</option>
                                <option value="annually" selected>سنوياً</option>
                            </select>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus me-1"></i>
                            إنشاء اشتراك
                        </button>
                    </div>
                </form>
            </div>
            @endif

            <!-- Quick Actions -->
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="fas fa-bolt me-2"></i>
                        إجراءات سريعة
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.clients.show', $client) }}" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-eye me-1"></i>
                            عرض تفاصيل العميل
                        </a>
                        
                        @if($client->clientProfile)
                            <button type="button" class="btn btn-outline-success btn-sm" onclick="renewSubscription()">
                                <i class="fas fa-sync me-1"></i>
                                تجديد الاشتراك
                            </button>
                            
                            <button type="button" class="btn btn-outline-warning btn-sm" onclick="suspendClient()">
                                <i class="fas fa-pause me-1"></i>
                                تعليق العميل
                            </button>
                        @endif
                        
                        <button type="button" class="btn btn-outline-info btn-sm" onclick="sendNotification()">
                            <i class="fas fa-bell me-1"></i>
                            إرسال إشعار
                        </button>
                        
                        <button type="button" class="btn btn-outline-danger btn-sm" onclick="deleteClient()">
                            <i class="fas fa-trash me-1"></i>
                            حذف العميل
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Device Management Section -->
    @if($client->clientProfile)
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fab fa-apple me-2"></i>
                        إدارة أجهزة Apple
                    </h5>
                    <button type="button" class="btn btn-primary btn-sm" onclick="showAddDeviceModal()">
                        <i class="fas fa-plus me-1"></i>
                        إضافة جهاز جديد
                    </button>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="small text-muted">استخدام الأجهزة</span>
                                <span class="small text-muted">
                                    {{ $client->clientDevices->count() }} / {{ $client->clientProfile->device_limit }}
                                </span>
                            </div>
                            <div class="progress" style="height: 6px;">
                                @php
                                    $percentage = $client->clientProfile->device_limit > 0 ? 
                                                 ($client->clientDevices->count() / $client->clientProfile->device_limit) * 100 : 0;
                                    $colorClass = $percentage >= 90 ? 'bg-danger' : ($percentage >= 70 ? 'bg-warning' : 'bg-success');
                                @endphp
                                <div class="progress-bar {{ $colorClass }}" style="width: {{ min($percentage, 100) }}%"></div>
                            </div>
                        </div>
                    </div>

                    @if($client->clientDevices->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>الجهاز</th>
                                        <th>النوع</th>
                                        <th>الرقم التسلسلي</th>
                                        <th>الحالة</th>
                                        <th>تاريخ التفعيل</th>
                                        <th>الإجراءات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($client->clientDevices as $device)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <i class="fab fa-apple text-muted me-2"></i>
                                                <div>
                                                    <strong>{{ $device->device_name }}</strong>
                                                    @if($device->device_model)
                                                        <br><small class="text-muted">{{ $device->device_model }}</small>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ $device->device_type }}</td>
                                        <td>
                                            @if($device->serial_number)
                                                <code>{{ $device->serial_number }}</code>
                                            @else
                                                <span class="text-muted">غير محدد</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($device->is_active)
                                                <span class="badge bg-success">نشط</span>
                                            @else
                                                <span class="badge bg-secondary">غير نشط</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($device->activated_at)
                                                {{ $device->activated_at->format('Y/m/d') }}
                                            @else
                                                <span class="text-muted">غير مُفعل</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <button type="button" class="btn btn-sm btn-outline-primary" 
                                                        onclick="editDevice({{ $device->id }})" title="تعديل">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-outline-{{ $device->is_active ? 'warning' : 'success' }}" 
                                                        onclick="toggleDeviceStatus({{ $device->id }})" 
                                                        title="{{ $device->is_active ? 'إيقاف' : 'تفعيل' }}">
                                                    <i class="fas fa-{{ $device->is_active ? 'pause' : 'play' }}"></i>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-outline-danger" 
                                                        onclick="deleteDevice({{ $device->id }})" title="حذف">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fab fa-apple text-muted fa-3x mb-3"></i>
                            <h6 class="text-muted">لا توجد أجهزة مسجلة</h6>
                            <p class="text-muted small">يمكن للعميل إضافة أجهزة من التطبيق أو يمكنك إضافتها من هنا</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

<!-- Add Device Modal -->
<div class="modal fade" id="addDeviceModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">إضافة جهاز Apple جديد</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.clients.add-device', $client) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="device_name" class="form-label">اسم الجهاز *</label>
                        <input type="text" class="form-control" id="device_name" name="device_name" required
                               placeholder="مثال: iPhone الخاص بأحمد">
                    </div>

                    <div class="mb-3">
                        <label for="device_type" class="form-label">نوع الجهاز *</label>
                        <select class="form-select" id="device_type" name="device_type" required>
                            <option value="">اختر نوع الجهاز</option>
                            <option value="iPhone">iPhone</option>
                            <option value="iPad">iPad</option>
                            <option value="Mac">Mac</option>
                            <option value="Apple TV">Apple TV</option>
                            <option value="Apple Watch">Apple Watch</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="serial_number" class="form-label">الرقم التسلسلي</label>
                        <input type="text" class="form-control" id="serial_number" name="serial_number"
                               placeholder="مثال: FCDP123ABC456">
                    </div>

                    <div class="mb-3">
                        <label for="device_model" class="form-label">موديل الجهاز</label>
                        <input type="text" class="form-control" id="device_model" name="device_model"
                               placeholder="مثال: iPhone 14 Pro Max">
                    </div>

                    <div class="mb-3">
                        <label for="ios_version" class="form-label">إصدار iOS</label>
                        <input type="text" class="form-control" id="ios_version" name="ios_version"
                               placeholder="مثال: 17.2.1">
                    </div>

                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" checked>
                        <label class="form-check-label" for="is_active">
                            تفعيل الجهاز فوراً
                        </label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-primary">إضافة الجهاز</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
function resetForm() {
    document.getElementById('clientForm').reset();
}

function showAddDeviceModal() {
    $('#addDeviceModal').modal('show');
}

function editDevice(deviceId) {
    // Redirect to device edit page or show edit modal
    alert('تعديل الجهاز #' + deviceId);
}

function toggleDeviceStatus(deviceId) {
    if (confirm('هل تريد تغيير حالة هذا الجهاز؟')) {
        fetch(`/admin/clients/{{ $client->id }}/devices/${deviceId}/toggle`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert(data.message || 'حدث خطأ في تغيير حالة الجهاز');
            }
        })
        .catch(() => {
            alert('حدث خطأ في الاتصال');
        });
    }
}

function deleteDevice(deviceId) {
    if (confirm('هل تريد حذف هذا الجهاز؟ لا يمكن التراجع عن هذا الإجراء.')) {
        fetch(`/admin/clients/{{ $client->id }}/devices/${deviceId}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert(data.message || 'حدث خطأ في حذف الجهاز');
            }
        })
        .catch(() => {
            alert('حدث خطأ في الاتصال');
        });
    }
}

function renewSubscription() {
    alert('تجديد الاشتراك');
}

function suspendClient() {
    if (confirm('هل تريد تعليق هذا العميل؟ سيتم إيقاف جميع خدماته.')) {
        alert('تعليق العميل');
    }
}

function sendNotification() {
    alert('إرسال إشعار للعميل');
}

function deleteClient() {
    if (confirm('هل تريد حذف هذا العميل نهائياً؟ سيتم حذف جميع بياناته.')) {
        window.location.href = '{{ route('admin.clients.show', $client) }}';
    }
}
</script>
@endsection