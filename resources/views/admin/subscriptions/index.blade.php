@extends('layouts.app')

@section('content')

<x-breadcrumb :items="[
    ['label' => 'لوحة التحكم', 'url' => route('admin.dashboard')],
    ['label' => 'الاشتراكات', 'url' => route('admin.subscriptions.index')],
]" />
<div class="container-fluid">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1">إدارة الاشتراكات</h2>
            <p class="text-muted mb-0">إدارة شاملة للاشتراكات والأجهزة المرتبطة</p>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#filtersModal">
                <i class="fas fa-filter"></i> فلترة
            </button>
            <button class="btn btn-success" onclick="exportSubscriptions()">
                <i class="fas fa-download"></i> تصدير
            </button>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-2">
            <div class="card border-0 bg-primary text-white">
                <div class="card-body text-center">
                    <h3 class="mb-1">{{ number_format($stats['total']) }}</h3>
                    <small>إجمالي الاشتراكات</small>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card border-0 bg-success text-white">
                <div class="card-body text-center">
                    <h3 class="mb-1">{{ number_format($stats['active']) }}</h3>
                    <small>اشتراكات فعالة</small>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card border-0 bg-warning text-white">
                <div class="card-body text-center">
                    <h3 class="mb-1">{{ number_format($stats['pending']) }}</h3>
                    <small>في الانتظار</small>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card border-0 bg-danger text-white">
                <div class="card-body text-center">
                    <h3 class="mb-1">{{ number_format($stats['expired']) }}</h3>
                    <small>منتهية الصلاحية</small>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card border-0 bg-secondary text-white">
                <div class="card-body text-center">
                    <h3 class="mb-1">{{ number_format($stats['suspended']) }}</h3>
                    <small>معلقة</small>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card border-0 bg-info text-white">
                <div class="card-body text-center">
                    <h3 class="mb-1">{{ number_format($stats['total_revenue']) }} ر.س</h3>
                    <small>إجمالي الإيرادات</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Subscriptions Table -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">قائمة الاشتراكات</h5>
            <div class="d-flex gap-2">
                <input type="text" class="form-control form-control-sm" placeholder="بحث في العملاء..." 
                       id="clientSearch" style="width: 200px;" value="{{request('client_search')}}">
                <select class="form-select form-select-sm" id="statusFilter" style="width: 150px;">
                    <option {{ request('status') == 'all' ? 'selected' : ''}} value="all">جميع الحالات</option>
                    <option {{ request('status') == 'pending' ? 'selected' : ''}} value="pending">في الانتظار</option>
                    <option {{ request('status') == 'active' ? 'selected' : ''}} value="active">نشط</option>
                    <option {{ request('status') == 'expired' ? 'selected' : ''}} value="expired">منتهي</option>
                    <option {{ request('status') == 'suspended' ? 'selected' : ''}} value="suspended">معلق</option>
                </select>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>العميل</th>
                            <th>الحالة</th>
                            <th>تاريخ التفعيل</th>
                            <th>تاريخ الانتهاء</th>
                            <th>عدد الأجهزة</th>
                            <th>المبلغ المدفوع</th>
                            <th>حالة الدفع</th>
                            <th>الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($subscriptions as $subscription)
                        <tr id="subscription-{{ $subscription->id }}">
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0">
                                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" 
                                             style="width: 40px; height: 40px; font-size: 14px; font-weight: bold;">
                                            {{ substr($subscription->client->name, 0, 2) }}
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h6 class="mb-0">{{ $subscription->client->name }}</h6>
                                        <small class="text-muted">{{ $subscription->client->email }}</small><br>
                                        <small class="text-muted">{{ $subscription->client->phone }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                @switch($subscription->status)
                                    @case('active')
                                        <span class="badge bg-success">نشط</span>
                                        @if($subscription->expires_at && $subscription->expires_at < now()->addDays(30))
                                            <br><small class="text-warning">ينتهي قريباً</small>
                                        @endif
                                        @break
                                    @case('pending')
                                        <span class="badge bg-warning">في الانتظار</span>
                                        @break
                                    @case('expired')
                                        <span class="badge bg-danger">منتهي</span>
                                        @break
                                    @case('suspended')
                                        <span class="badge bg-secondary">معلق</span>
                                        @break
                                    @default
                                        <span class="badge bg-light text-dark">غير محدد</span>
                                @endswitch
                            </td>
                            <td>
                                @if($subscription->activated_at)
                                    {{ $subscription->activated_at->format('Y/m/d') }}
                                    <br><small class="text-muted">{{ $subscription->activated_at->diffForHumans() }}</small>
                                @else
                                    <span class="text-muted">لم يفعل بعد</span>
                                @endif
                            </td>
                            <td>
                                @if($subscription->expires_at)
                                    {{ $subscription->expires_at->format('Y/m/d') }}
                                    <br><small class="text-muted">
                                        @if($subscription->expires_at > now())
                                            {{ $subscription->expires_at->diffForHumans() }}
                                        @else
                                            <span class="text-danger">منتهي منذ {{ $subscription->expires_at->diffForHumans() }}</span>
                                        @endif
                                    </small>
                                @else
                                    <span class="text-muted">غير محدد</span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <span class="badge bg-info me-2">{{ $subscription->devices->count() }}</span>
                                    <small class="text-success">{{ $subscription->devices->where('is_active', true)->count() }} نشط</small>
                                </div>
                            </td>
                            <td>
                                @if($subscription->payment)
                                    <strong>{{ number_format($subscription->payment->amount) }} ر.س</strong>
                                @else
                                    <span class="text-muted">غير محدد</span>
                                @endif
                            </td>
                            <td>
                                @if($subscription->payment)
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
                                        @default
                                            <span class="badge bg-light text-dark">غير محدد</span>
                                    @endswitch
                                @else
                                    <span class="text-muted">لا يوجد دفع</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <button type="button" class="btn btn-sm btn-outline-primary" 
                                            onclick="viewSubscription({{ $subscription->id }})">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    
                                    @if($subscription->status === 'pending')
                                        <button type="button" class="btn btn-sm btn-outline-success" 
                                                onclick="showActivateModal({{ $subscription->id }})">
                                            <i class="fas fa-play"></i>
                                        </button>
                                    @endif
                                    
                                    @if($subscription->status === 'active')
                                        <button type="button" class="btn btn-sm btn-outline-warning" 
                                                onclick="showSuspendModal({{ $subscription->id }})">
                                            <i class="fas fa-pause"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-info" 
                                                onclick="showRenewModal({{ $subscription->id }})">
                                            <i class="fas fa-sync"></i>
                                        </button>
                                    @endif
                                    
                                    @if($subscription->status === 'suspended')
                                        <button type="button" class="btn btn-sm btn-outline-success" 
                                                onclick="showActivateModal({{ $subscription->id }})">
                                            <i class="fas fa-play"></i>
                                        </button>
                                    @endif
                                    
                                    <button type="button" class="btn btn-sm btn-outline-danger" 
                                            onclick="deleteSubscription({{ $subscription->id }})">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-5">
                                <div class="text-muted">
                                    <i class="fas fa-inbox fa-3x mb-3"></i>
                                    <h5>لا توجد اشتراكات</h5>
                                    <p>لم يتم العثور على أي اشتراكات مطابقة للمعايير المحددة</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($subscriptions->hasPages())
        <div class="card-footer">
            {{ $subscriptions->links() }}
        </div>
        @endif
    </div>
</div>

<!-- Activate Subscription Modal -->
<div class="modal fade" id="activateModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">تفعيل الاشتراك</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="activateForm">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">تاريخ التفعيل</label>
                        <input type="date" class="form-control" name="activation_date" 
                               value="{{ now()->format('Y-m-d') }}" required>
                        <div class="form-text">سيتم حساب تاريخ الانتهاء تلقائياً (بعد سنة من تاريخ التفعيل)</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">ملاحظات إدارية</label>
                        <textarea class="form-control" name="notes" rows="3" 
                                  placeholder="أي ملاحظات خاصة بالتفعيل..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-success">تفعيل الاشتراك</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Suspend Subscription Modal -->
<div class="modal fade" id="suspendModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">تعليق الاشتراك</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="suspendForm">
                <div class="modal-body">
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        سيتم تعليق جميع الأجهزة المرتبطة بهذا الاشتراك
                    </div>
                    <div class="mb-3">
                        <label class="form-label">سبب التعليق *</label>
                        <textarea class="form-control" name="reason" rows="3" required
                                  placeholder="يرجى توضيح سبب تعليق الاشتراك..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-warning">تعليق الاشتراك</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Renew Subscription Modal -->
<div class="modal fade" id="renewModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">تجديد الاشتراك</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="renewForm">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">فترة التجديد</label>
                        <select class="form-select" name="renewal_period" required>
                            <option value="1_year">سنة واحدة</option>
                            <option value="6_months">6 أشهر</option>
                            <option value="3_months">3 أشهر</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">مبلغ التجديد</label>
                        <input type="number" class="form-control" name="payment_amount" 
                               placeholder="0.00" step="0.01" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-success">تجديد الاشتراك</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Search and filter functionality
    $('#clientSearch').on('blur', function() {
        filterSubscriptions();
    });
    
    $('#statusFilter').on('change', function() {
        filterSubscriptions();
    });
    
    // Activate subscription
    $('#activateForm').on('submit', function(e) {
        e.preventDefault();
        const subscriptionId = $(this).data('subscription-id');
        const formData = new FormData(this);
        
        $.ajax({
            url: `/admin/subscriptions/${subscriptionId}/activate`,
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    $('#activateModal').modal('hide');
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
    
    // Suspend subscription
    $('#suspendForm').on('submit', function(e) {
        e.preventDefault();
        const subscriptionId = $(this).data('subscription-id');
        const formData = new FormData(this);
        
        $.ajax({
            url: `/admin/subscriptions/${subscriptionId}/suspend`,
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    $('#suspendModal').modal('hide');
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
    
    // Renew subscription
    $('#renewForm').on('submit', function(e) {
        e.preventDefault();
        const subscriptionId = $(this).data('subscription-id');
        const formData = new FormData(this);
        
        $.ajax({
            url: `/admin/subscriptions/${subscriptionId}/renew`,
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    $('#renewModal').modal('hide');
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

function viewSubscription(subscriptionId) {
    window.open(`/admin/subscriptions/${subscriptionId}`, '_blank');
}

function showActivateModal(subscriptionId) {
    $('#activateForm').data('subscription-id', subscriptionId);
    $('#activateModal').modal('show');
}

function showSuspendModal(subscriptionId) {
    $('#suspendForm').data('subscription-id', subscriptionId);
    $('#suspendModal').modal('show');
}

function showRenewModal(subscriptionId) {
    $('#renewForm').data('subscription-id', subscriptionId);
    $('#renewModal').modal('show');
}

function editSubscription(subscriptionId) {
    // Redirect to edit page
    window.location.href = `/admin/subscriptions/${subscriptionId}/edit`;
}

function deleteSubscription(subscriptionId) {
    if (confirm('هل أنت متأكد من حذف هذا الاشتراك؟ سيتم حذف جميع البيانات المرتبطة به.')) {
        $.ajax({
            url: `/admin/subscriptions/${subscriptionId}`,
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    $(`#subscription-${subscriptionId}`).fadeOut();
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

function filterSubscriptions() {
    const clientSearch = $('#clientSearch').val().toLowerCase();
    const statusFilter = $('#statusFilter').val();
    
    const params = new URLSearchParams();
    if (clientSearch) params.append('client_search', clientSearch);
    if (statusFilter) params.append('status', statusFilter);
    
    const url = window.location.pathname + (params.toString() ? '?' + params.toString() : '');
    window.location.href = url;
}

function exportSubscriptions() {
    window.location.href = '/admin/subscriptions/export?' + new URLSearchParams({
        client_search: $('#clientSearch').val() || '',
        status: $('#statusFilter').val() || ''
    }).toString();
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
    }, 25000);
}
</script>
@endsection