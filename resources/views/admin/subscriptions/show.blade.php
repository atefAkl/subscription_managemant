@extends('layouts.app')

@section('content')
<div class="container">

    <x-breadcrumb :items="[
        ['label' => 'لوحة التحكم', 'url' => route('admin.dashboard')],
        ['label' => 'الاشتراكات', 'url' => route('admin.subscriptions.index')],
        ['label' => 'تفاصيل الاشتراك']
    ]" />
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1">تفاصيل اشتراك {{ $subscription->client->name }}</h2>
            <p class="text-muted mb-0">إدارة شاملة للاشتراك والأجهزة المرتبطة</p>
        </div>

    </div>

    <div class="row">
        <!-- Subscription Info -->
        <div class="col-lg-4">
            <div class="card mb-4 shadow">
                <div class="card-header py-2">
                    <h3 class="mb-0">معلومات الاشتراك</h3>
                </div>

                <div class="card-body">
                    <div class="grid grid-cols-1 md:grid-cols-2 mb-3">
                        <div class="mb-3">
                            <label class="form-label text-muted">بيان الاشتراك</label>
                            <p>{{$subscription->subscription_name ?? 'No details added'}}</p>
                        </div>

                        <div class="mb-3">
                            <label class="form-label text-muted">عدد الأجهزة </label>
                            <p>{{$subscription->device_count ?? 'لم يتم اضافة أجهزة بعد'}}</p>
                        </div>

                        <div class="mb-3">
                            <label class="form-label text-muted">بداية مقترحة </label>
                            <p>{{$subscription->proposed_start_date->format('Y-m-d') ?? 'لم يتم اضافة أجهزة بعد'}}</p>
                        </div>

                        <div class="mb-3">
                            <label class="form-label text-muted">المبلغ المسدد</label>
                            <p><strong>{{ number_format($subscription->payment->amount ?? 0) }} ر.س</strong> </p>
                        </div>
                        <style>
                            img.payment-method-icon {
                                max-height: 40px;
                                border-radius: 8px
                            }
                        </style>

                        <div class="mb-3">
                            <label class="form-label text-muted">طريقة الدفع</label>
                            @switch($subscription->payment_method)
                            @case('bank_transfer')
                            <img class="payment-method-icon" src="{{asset('images/bank-transfer.png')}}" alt="">
                            @break
                            @case('vodafone_cash')
                            <img class="payment-method-icon" src="{{asset('images/vf-cash.png')}}" alt="">
                            @break
                            @case('orange_cash')
                            <img class="payment-method-icon" src="{{asset('images/orange-cash.png')}}" alt="">
                            @break
                            @case('etisalat_cash')
                            <img class="payment-method-icon" src="{{asset('images/etisalat-cash.png')}}" alt="">
                            @break
                            @case('visa_card')
                            <img class="payment-method-icon" src="{{asset('images/visa-card.png')}}" alt="">
                            @break
                            @default
                            <p>غير معروف</p>
                            @endswitch
                        </div>

                        <div class="mb-3">
                            <label class="form-label text-muted">الايصال</label><br>
                            @php $requestReceipt = optional($subscription->subscriptionRequest)->payment_receipt; @endphp
                            @if($requestReceipt)
                            <button class="btn btn-primary btn-sm" onclick="window.open('{{asset('storage/' . $requestReceipt)}}', '_blank')">عرض الصورة</button>
                            @else
                            <p>لم يتم ارسال شيك الدفع</p>
                            @endif
                        </div>
                    </div>

                    <div class="grid grid-cols-1">
                        <label class="form-label text-muted">ملاحظات العميل</label>
                        @php $requestNotes = optional($subscription->subscriptionRequest)->notes; @endphp
                        <p>{{ $requestNotes ?? 'لا يوجد ملاحظات مسجلة'}} </p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-8">
            <div class="card mb-4 shadow">
                <div class="card-header py-2">
                    <h3 class="mb-0">ادارة الاشتراك</h3>
                </div>
                <div class="card-body">

                    <div class="grid grid-cols-1 md:grid-cols-2">
                        {{-- Administration Area --}}
                        <div class="mb-3">
                            <label class="form-label text-muted">حالة الاشتراك</label>
                            <div>
                                @switch($subscription->status)
                                @case('active')
                                <span class="badge bg-success fs-6">نشط</span>
                                @break
                                @case('pending')
                                <span class="badge bg-warning fs-6">في الانتظار</span>
                                <a href="{{route('admin.subscriptions.quote', $subscription->id)}}" class="btn btn-primary btn-sm">Quote</a>
                                @break
                                @case('expired')
                                <span class="badge bg-danger fs-6">منتهي</span>
                                @break
                                @case('quoted')
                                <span class="badge bg-secondary fs-6">تم ارسال عرض سعر</span>
                                @break
                                @case('suspended')
                                <span class="badge bg-secondary fs-6">معلق</span>
                                @break
                                @endswitch
                            </div>
                        </div>

                        @if(isset($timeline['activated']) && $timeline['activated'])
                        <div class="mb-3">
                            <label class="form-label text-muted">تاريخ التفعيل</label>
                            <div>
                                <strong>{{ $timeline['activated']->format('Y/m/d') }}</strong>
                                <small class="text-muted d-block">{{ $timeline['activated']->diffForHumans() }}</small>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label text-muted">تاريخ الانتهاء</label>
                            <div>
                                <strong>{{ $timeline['expires']->format('Y/m/d') }}</strong>
                                <small class="text-muted d-block">
                                    @if($timeline['days_remaining'] > 0)
                                    <span class="text-success">باقي {{ $timeline['days_remaining'] }} يوم</span>
                                    @else
                                    <span class="text-danger">منتهي منذ {{ abs($timeline['days_remaining']) }} يوم</span>
                                    @endif
                                </small>
                            </div>
                        </div>
                        @endif

                        @if($subscription->payment)
                        <div class="mb-3">
                            <label class="form-label text-muted">المبلغ المدفوع</label>
                            <div>

                                <div class="mt-1">
                                    @switch($subscription->payment->status)
                                    @case('verified')
                                    <span class="badge bg-success">مؤكد</span>
                                    @break
                                    @case('pending_verification')
                                    <span class="badge bg-warning">في الانتظار</span>
                                    @break
                                    @case('rejected')
                                    <span class="badge bg-danger">مرفوض</span>
                                    @break
                                    @endswitch
                                </div>
                            </div>
                        </div>
                        @endif

                        <div class="mb-3">
                            <label class="form-label text-muted">تاريخ الطلب</label>
                            <div>
                                <strong>{{ $subscription->created_at->format('Y/m/d H:i') }}</strong>
                                <small class="text-muted d-block">{{ $subscription->created_at->diffForHumans() }}</small>
                            </div>
                        </div>

                        @if($subscription->admin_notes)
                        <div class="mb-3">
                            <label class="form-label text-muted">ملاحظات إدارية</label>
                            <div class="bg-light p-2 rounded">
                                {{ $subscription->admin_notes }}
                            </div>
                        </div>
                        @endif

                        <!-- Action Buttons -->
                        <div class="d-grid gap-2 mt-4">
                            @if($subscription->status === 'pending')
                            <button class="btn btn-success" onclick="showActivateModal()">
                                <i class="fas fa-play"></i> تفعيل الاشتراك
                            </button>
                            @endif

                            @if($subscription->status === 'active')
                            <button class="btn btn-warning" onclick="showSuspendModal()">
                                <i class="fas fa-pause"></i> تعليق الاشتراك
                            </button>
                            <button class="btn btn-info" onclick="showRenewModal()">
                                <i class="fas fa-sync"></i> تجديد الاشتراك
                            </button>
                            @endif

                            @if($subscription->status === 'suspended')
                            <button class="btn btn-success" onclick="showActivateModal()">
                                <i class="fas fa-play"></i> إعادة تفعيل
                            </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Device Statistics -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">إحصائيات الأجهزة</h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6">
                            <h3 class="text-primary">{{ $device_stats['total'] }}</h3>
                            <small>إجمالي الأجهزة</small>
                        </div>
                        <div class="col-6">
                            <h3 class="text-success">{{ $device_stats['active'] }}</h3>
                            <small>الأجهزة النشطة</small>
                        </div>
                    </div>

                    @if($device_stats['by_type']->count() > 0)
                    <hr>
                    <div class="mb-2"><strong>توزيع الأجهزة:</strong></div>
                    @foreach($device_stats['by_type'] as $type)
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <span>{{ $type->device_type }}</span>
                        <span class="badge bg-info">{{ $type->count }}</span>
                    </div>
                    @endforeach
                    @endif
                </div>
            </div>
        </div>

        <!-- Devices Management -->
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">إدارة الأجهزة ({{ $subscription->devices->count() }})</h5>
                    <button class="btn btn-primary btn-sm" onclick="showAddDeviceModal()">
                        <i class="fas fa-plus"></i> إضافة جهاز
                    </button>
                </div>
                <div class="card-body">
                    @if($subscription->devices->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>اسم الجهاز</th>
                                    <th>النوع</th>
                                    <th>الرقم التسلسلي</th>
                                    <th>الحالة</th>
                                    <th>تاريخ التفعيل</th>
                                    <th>الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($subscription->devices as $device)
                                <tr id="device-{{ $device->id }}">
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <i class="fas {{ getDeviceIcon($device->device_type) }} me-2 text-primary"></i>
                                            <div>
                                                <strong>{{ $device->device_name }}</strong>
                                                @if($device->model)
                                                <br><small class="text-muted">{{ $device->model }}</small>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $device->device_type }}</td>
                                    <td>
                                        <code>{{ $device->serial_number }}</code>
                                        @if($device->ios_version)
                                        <br><small class="text-muted">iOS {{ $device->ios_version }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        @if($device->is_active)
                                        <span class="badge bg-success">نشط</span>
                                        @else
                                        <span class="badge bg-secondary">معلق</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($device->activated_at)
                                        {{ $device->activated_at->format('Y/m/d') }}
                                        <br><small class="text-muted">{{ $device->activated_at->diffForHumans() }}</small>
                                        @else
                                        <span class="text-muted">غير مفعل</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group" aria-label="Device Actions">
                                            @if($device->is_active)
                                            <button type="button" class="btn btn-sm btn-outline-warning" onclick="suspendDevice({{ $device->id }})" title="تعليق الجهاز">
                                                <i class="fas fa-pause"></i>
                                            </button>
                                            @else
                                            <button type="button" class="btn btn-sm btn-outline-success" onclick="activateDevice({{ $device->id }})" title="تفعيل الجهاز">
                                                <i class="fas fa-play"></i>
                                            </button>
                                            @endif

                                            <button type="button" class="btn btn-sm btn-outline-primary" onclick="editDevice({{ $device->id }})" title="تعديل الجهاز">
                                                <i class="fas fa-edit"></i>
                                            </button>

                                            <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeDevice({{ $device->id }})" title="حذف الجهاز">
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
                    <div class="text-center py-5">
                        <i class="fas fa-mobile-alt fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">لا توجد أجهزة مسجلة</h5>
                        <p class="text-muted">ابدأ بإضافة الأجهزة المرتبطة بهذا الاشتراك</p>
                        <button class="btn btn-primary" onclick="showAddDeviceModal()">
                            <i class="fas fa-plus"></i> إضافة جهاز جديد
                        </button>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Device Modal -->
<div class="modal fade" id="addDeviceModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">إضافة جهاز جديد</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="addDeviceForm">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">اسم الجهاز *</label>
                                <input type="text" class="form-control" name="device_name" required placeholder="مثال: iPhone الخاص بأحمد">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">نوع الجهاز *</label>
                                <select class="form-select" name="device_type" required>
                                    <option value="">اختر نوع الجهاز</option>
                                    <option value="iPhone">iPhone</option>
                                    <option value="iPad">iPad</option>
                                    <option value="Mac">Mac</option>
                                    <option value="Apple TV">Apple TV</option>
                                    <option value="Apple Watch">Apple Watch</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">الرقم التسلسلي *</label>
                                <input type="text" class="form-control" name="serial_number" required placeholder="مثال: FCDP123ABC456">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">الموديل</label>
                                <input type="text" class="form-control" name="model" placeholder="مثال: iPhone 14 Pro Max">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">إصدار iOS</label>
                                <input type="text" class="form-control" name="ios_version" placeholder="مثال: 17.2.1">
                            </div>
                        </div>
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

<!-- Edit Device Modal -->
<div class="modal fade" id="editDeviceModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">تعديل الجهاز</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editDeviceForm">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">اسم الجهاز *</label>
                                <input type="text" class="form-control" name="device_name" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">الموديل</label>
                                <input type="text" class="form-control" name="model">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">إصدار iOS</label>
                                <input type="text" class="form-control" name="ios_version">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-primary">حفظ التغييرات</button>
                </div>
            </form>
        </div>
    </div>
</div>

@include('admin.subscriptions.modals')

@endsection

@section('scripts')
<script>
    const subscriptionId = {{ $subscription->id }};

$(document).ready(function() {
    // Add device form
    $('#addDeviceForm').on('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        
        $.ajax({
            url: `/admin/subscriptions/${subscriptionId}/devices`,
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    $('#addDeviceModal').modal('hide');
                    showAlert('success', response.message);
                    location.reload();
                }
            },
            error: function(xhr) {
                const response = xhr.responseJSON;
                showAlert('danger', response.message || 'حدث خطأ غير متوقع');
            }
        });
    });
    
    // Edit device form
    $('#editDeviceForm').on('submit', function(e) {
        e.preventDefault();
        const deviceId = $(this).data('device-id');
        const formData = new FormData(this);
        formData.append('_method', 'PUT');
        
        $.ajax({
            url: `/admin/subscriptions/${subscriptionId}/devices/${deviceId}`,
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    $('#editDeviceModal').modal('hide');
                    showAlert('success', response.message);
                    location.reload();
                }
            },
            error: function(xhr) {
                const response = xhr.responseJSON;
                showAlert('danger', response.message || 'حدث خطأ غير متوقع');
            }
        });
    });
});

function showAddDeviceModal() {
    $('#addDeviceModal').modal('show');
}

function editDevice(deviceId) {
    // You can load device data here if needed
    $('#editDeviceForm').data('device-id', deviceId);
    $('#editDeviceModal').modal('show');
}

function activateDevice(deviceId) {
    $.ajax({
        url: `/admin/subscriptions/${subscriptionId}/devices/${deviceId}/activate`,
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            if (response.success) {
                showAlert('success', response.message);
                location.reload();
            }
        },
        error: function(xhr) {
            const response = xhr.responseJSON;
            showAlert('danger', response.message || 'حدث خطأ غير متوقع');
        }
    });
}

function suspendDevice(deviceId) {
    if (confirm('هل أنت متأكد من تعليق هذا الجهاز؟')) {
        $.ajax({
            url: `/admin/subscriptions/${subscriptionId}/devices/${deviceId}/suspend`,
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    showAlert('success', response.message);
                    location.reload();
                }
            },
            error: function(xhr) {
                const response = xhr.responseJSON;
                showAlert('danger', response.message || 'حدث خطأ غير متوقع');
            }
        });
    }
}

function removeDevice(deviceId) {
    if (confirm('هل أنت متأكد من حذف هذا الجهاز؟ لا يمكن التراجع عن هذا الإجراء.')) {
        $.ajax({
            url: `/admin/subscriptions/${subscriptionId}/devices/${deviceId}`,
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    $(`#device-${deviceId}`).fadeOut();
                    showAlert('success', response.message);
                }
            },
            error: function(xhr) {
                const response = xhr.responseJSON;
                showAlert('danger', response.message || 'حدث خطأ غير متوقع');
            }
        });
    }
}

function showAlert(type, message) {
    const alertHtml = `
        <div class="alert alert-${type} alert-dismissible fade show position-fixed" 
             style="top: 20px; right: 20px; z-index: 9999; max-width: 400px;">
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    `;
    $('body').append(alertHtml);
    
    setTimeout(() => {
        $('.alert').fadeOut();
    }, 5000);
}
</script>
@endsection

@php
function getDeviceIcon($deviceType) {
return match($deviceType) {
'iPhone' => 'fa-mobile-alt',
'iPad' => 'fa-tablet-alt',
'Mac' => 'fa-laptop',
'Apple TV' => 'fa-tv',
'Apple Watch' => 'fa-clock',
default => 'fa-mobile-alt'
};
}
@endphp