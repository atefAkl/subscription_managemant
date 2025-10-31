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

<!-- Edit Subscription Modal -->
<div class="modal fade" id="editSubscriptionModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">تعديل بيانات الاشتراك</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editSubscriptionForm">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">تاريخ التفعيل</label>
                                <input type="date" class="form-control" name="activation_date" 
                                       value="{{ $subscription->activated_at ? $subscription->activated_at->format('Y-m-d') : '' }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">تاريخ الانتهاء</label>
                                <input type="date" class="form-control" name="expiry_date"
                                       value="{{ $subscription->expires_at ? $subscription->expires_at->format('Y-m-d') : '' }}">
                                <div class="form-text">اتركه فارغاً لحساب تلقائي (سنة من تاريخ التفعيل)</div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label">ملاحظات إدارية</label>
                                <textarea class="form-control" name="admin_notes" rows="4"
                                          placeholder="ملاحظات خاصة بالاشتراك...">{{ $subscription->admin_notes }}</textarea>
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

<script>
// Subscription management functions
function showActivateModal() {
    $('#activateModal').modal('show');
}

function showSuspendModal() {
    $('#suspendModal').modal('show');
}

function showRenewModal() {
    $('#renewModal').modal('show');
}

function editSubscription() {
    $('#editSubscriptionModal').modal('show');
}

$(document).ready(function() {
    // Activate subscription
    $('#activateForm').on('submit', function(e) {
        e.preventDefault();
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
    
    // Edit subscription
    $('#editSubscriptionForm').on('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        formData.append('_method', 'PUT');
        
        $.ajax({
            url: `/admin/subscriptions/${subscriptionId}`,
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    $('#editSubscriptionModal').modal('hide');
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
</script>