@extends('layouts.app')

@section('content')

<!-- Breadcrumb -->
<div class="max-w-7xl mx-auto py-2 sm:px-6 lg:px-8">
    <div class="bg-white rounded shadow-sm px-4 py-2 mb-0 md:flex md:items-center md:justify-between">
        <x-breadcrumb :items="[
        ['title' => 'لوحة التحكم', 'url' => route('admin.dashboard')],
        ['title' => 'إدارة العملاء', 'url' => route('admin.clients.index')],
        ['title' => 'إنشاء عميل جديد', 'url' => '']
    ]" />
    </div>
</div>
<div class="max-w-7xl mx-auto py-2 sm:px-6 lg:px-8">
    <div class="bg-white rounded shadow-sm px-4 py-2 mb-0 md:flex md:items-center md:justify-between">
        <div class="row">
            <div class="col-lg-10 offset-lg-1">
                <div class="card shadow-sm">
                    <!-- Header -->
                    <div class="card-header bg-primary text-white">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">
                                <i class="fas fa-user-plus me-2"></i>
                                إنشاء عميل جديد
                            </h5>
                            <small>جميع الحقول المميزة بـ * إلزامية</small>
                        </div>
                    </div>

                    <form action="{{ route('admin.clients.store') }}" method="POST" novalidate>
                        @csrf

                        <div class="card-body">
                            <!-- Section 1: Basic Information -->
                            <div class="mb-4">
                                <h6 class="text-uppercase text-muted mb-3">
                                    <i class="fas fa-user me-2"></i>المعلومات الأساسية
                                </h6>
                                <hr>

                                <div class="row">
                                    <!-- Name -->
                                    <div class="col-md-6 mb-3">
                                        <label for="name" class="form-label">
                                            الاسم الكامل <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}"
                                            placeholder="أدخل اسم العميل الكامل" required>
                                        @error('name')
                                        <div class="invalid-feedback d-block">
                                            <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                        </div>
                                        @enderror
                                    </div>

                                    <!-- Email -->
                                    <div class="col-md-6 mb-3">
                                        <label for="email" class="form-label">
                                            البريد الإلكتروني <span class="text-danger">*</span>
                                        </label>
                                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}"
                                            placeholder="example@domain.com" required>
                                        @error('email')
                                        <div class="invalid-feedback d-block">
                                            <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                        </div>
                                        @enderror
                                    </div>

                                    <!-- Phone -->
                                    <div class="col-md-6 mb-3">
                                        <label for="phone" class="form-label">رقم الهاتف</label>
                                        <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone') }}"
                                            placeholder="+966 50 0000000">
                                        @error('phone')
                                        <div class="invalid-feedback d-block">
                                            <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                        </div>
                                        @enderror
                                    </div>

                                    <!-- Password -->
                                    <div class="col-md-6 mb-3">
                                        <label for="password" class="form-label">
                                            كلمة المرور <span class="text-danger">*</span>
                                            <small class="text-muted d-block">(8 أحرف على الأقل)</small>
                                        </label>
                                        <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password"
                                            placeholder="أدخل كلمة المرور" minlength="8" required>
                                        @error('password')
                                        <div class="invalid-feedback d-block">
                                            <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                        </div>
                                        @enderror
                                    </div>

                                    <!-- Confirm Password -->
                                    <div class="col-md-6 mb-3">
                                        <label for="password_confirmation" class="form-label">
                                            تأكيد كلمة المرور <span class="text-danger">*</span>
                                        </label>
                                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="أعد كتابة كلمة المرور"
                                            minlength="8" required>
                                    </div>

                                    <!-- Address -->
                                    <div class="col-12 mb-3">
                                        <label for="address" class="form-label">العنوان</label>
                                        <textarea class="form-control @error('address') is-invalid @enderror" id="address" name="address" rows="2" placeholder="أدخل عنوان العميل"
                                            maxlength="1000">{{ old('address') }}</textarea>
                                        <small class="text-muted">الحد الأقصى 1000 حرف</small>
                                        @error('address')
                                        <div class="invalid-feedback d-block">
                                            <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                        </div>
                                        @enderror
                                    </div>

                                    <!-- Notes -->
                                    <div class="col-12 mb-3">
                                        <label for="notes" class="form-label">ملاحظات عامة</label>
                                        <textarea class="form-control @error('notes') is-invalid @enderror" id="notes" name="notes" rows="2"
                                            placeholder="أي ملاحظات إضافية عن العميل" maxlength="1000">{{ old('notes') }}</textarea>
                                        <small class="text-muted">الحد الأقصى 1000 حرف</small>
                                        @error('notes')
                                        <div class="invalid-feedback d-block">
                                            <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Section 2: Subscription Information -->
                            <div class="mb-4">
                                <h6 class="text-uppercase text-muted mb-3">
                                    <i class="fas fa-crown me-2"></i>معلومات الاشتراك
                                </h6>
                                <hr>

                                <div class="row">
                                    <!-- Subscription Type -->
                                    <div class="col-md-4 mb-3">
                                        <label for="subscription_type" class="form-label">
                                            نوع الاشتراك <span class="text-danger">*</span>
                                        </label>
                                        <select class="form-select @error('subscription_type') is-invalid @enderror" id="subscription_type" name="subscription_type" required>
                                            <option value="">-- اختر نوع الاشتراك --</option>
                                            <option value="basic" {{ old('subscription_type') === 'basic' ? 'selected' : '' }}>
                                                أساسي (Basic)
                                            </option>
                                            <option value="premium" {{ old('subscription_type') === 'premium' ? 'selected' : '' }}>
                                                مميز (Premium)
                                            </option>
                                            <option value="enterprise" {{ old('subscription_type') === 'enterprise' ? 'selected' : '' }}>
                                                مؤسسي (Enterprise)
                                            </option>
                                        </select>
                                        @error('subscription_type')
                                        <div class="invalid-feedback d-block">
                                            <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                        </div>
                                        @enderror
                                    </div>

                                    <!-- Subscription Status -->
                                    <div class="col-md-4 mb-3">
                                        <label for="subscription_status" class="form-label">
                                            حالة الاشتراك <span class="text-danger">*</span>
                                        </label>
                                        <select class="form-select @error('subscription_status') is-invalid @enderror" id="subscription_status" name="subscription_status" required>
                                            <option value="">-- اختر الحالة --</option>
                                            <option value="active" {{ old('subscription_status') === 'active' ? 'selected' : '' }}>نشط (Active)</option>
                                            <option value="inactive" {{ old('subscription_status') === 'inactive' ? 'selected' : '' }}>غير نشط (Inactive)</option>
                                            <option value="trial" {{ old('subscription_status') === 'trial' ? 'selected' : '' }}>تجريبي (Trial)</option>
                                            <option value="suspended" {{ old('subscription_status') === 'suspended' ? 'selected' : '' }}>معلق (Suspended)</option>
                                            <option value="expired" {{ old('subscription_status') === 'expired' ? 'selected' : '' }}>منتهي (Expired)</option>
                                        </select>
                                        @error('subscription_status')
                                        <div class="invalid-feedback d-block">
                                            <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                        </div>
                                        @enderror
                                    </div>

                                    <!-- Device Limit -->
                                    <div class="col-md-4 mb-3">
                                        <label for="device_limit" class="form-label">
                                            حد الأجهزة <span class="text-danger">*</span>
                                        </label>
                                        <input type="number" class="form-control @error('device_limit') is-invalid @enderror" id="device_limit" name="device_limit"
                                            value="{{ old('device_limit', 5) }}" min="1" max="50" placeholder="عدد الأجهزة المسموحة" required>
                                        <small class="text-muted">من 1 إلى 50 جهاز</small>
                                        @error('device_limit')
                                        <div class="invalid-feedback d-block">
                                            <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                        </div>
                                        @enderror
                                    </div>

                                    <!-- Subscription Start Date -->
                                    <div class="col-md-6 mb-3">
                                        <label for="subscription_start_date" class="form-label">تاريخ البداية</label>
                                        <input type="date" class="form-control @error('subscription_start_date') is-invalid @enderror" id="subscription_start_date"
                                            name="subscription_start_date" value="{{ old('subscription_start_date') }}">
                                        @error('subscription_start_date')
                                        <div class="invalid-feedback d-block">
                                            <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                        </div>
                                        @enderror
                                    </div>

                                    <!-- Subscription End Date -->
                                    <div class="col-md-6 mb-3">
                                        <label for="subscription_end_date" class="form-label">تاريخ الانتهاء</label>
                                        <input type="date" class="form-control @error('subscription_end_date') is-invalid @enderror" id="subscription_end_date"
                                            name="subscription_end_date" value="{{ old('subscription_end_date') }}">
                                        <small class="text-muted">يجب أن يكون بعد تاريخ البداية</small>
                                        @error('subscription_end_date')
                                        <div class="invalid-feedback d-block">
                                            <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                        </div>
                                        @enderror
                                    </div>

                                    <!-- Billing Cycle -->
                                    <div class="col-md-6 mb-3">
                                        <label for="billing_cycle" class="form-label">
                                            دورة الفوترة <span class="text-danger">*</span>
                                        </label>
                                        <select class="form-select @error('billing_cycle') is-invalid @enderror" id="billing_cycle" name="billing_cycle" required>
                                            <option value="">-- اختر دورة الفوترة --</option>
                                            <option value="monthly" {{ old('billing_cycle') === 'monthly' ? 'selected' : '' }}>شهري (Monthly)</option>
                                            <option value="quarterly" {{ old('billing_cycle') === 'quarterly' ? 'selected' : '' }}>ربع سنوي (Quarterly)</option>
                                            <option value="yearly" {{ old('billing_cycle') === 'yearly' ? 'selected' : '' }}>سنوي (Yearly)</option>
                                        </select>
                                        @error('billing_cycle')
                                        <div class="invalid-feedback d-block">
                                            <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                        </div>
                                        @enderror
                                    </div>

                                    <!-- Payment Status -->
                                    <div class="col-md-6 mb-3">
                                        <label for="payment_status" class="form-label">
                                            حالة الدفع <span class="text-danger">*</span>
                                        </label>
                                        <select class="form-select @error('payment_status') is-invalid @enderror" id="payment_status" name="payment_status" required>
                                            <option value="">-- اختر حالة الدفع --</option>
                                            <option value="paid" {{ old('payment_status') === 'paid' ? 'selected' : '' }}>مدفوع (Paid)</option>
                                            <option value="pending" {{ old('payment_status') === 'pending' ? 'selected' : '' }}>في الانتظار (Pending)</option>
                                            <option value="overdue" {{ old('payment_status') === 'overdue' ? 'selected' : '' }}>متأخر (Overdue)</option>
                                            <option value="failed" {{ old('payment_status') === 'failed' ? 'selected' : '' }}>فشل (Failed)</option>
                                        </select>
                                        @error('payment_status')
                                        <div class="invalid-feedback d-block">
                                            <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                        </div>
                                        @enderror
                                    </div>

                                    <!-- Client Notes -->
                                    <div class="col-12 mb-3">
                                        <label for="client_notes" class="form-label">ملاحظات الاشتراك</label>
                                        <textarea class="form-control @error('client_notes') is-invalid @enderror" id="client_notes" name="client_notes" rows="2"
                                            placeholder="ملاحظات خاصة بالاشتراك" maxlength="1000">{{ old('client_notes') }}</textarea>
                                        <small class="text-muted">الحد الأقصى 1000 حرف</small>
                                        @error('client_notes')
                                        <div class="invalid-feedback d-block">
                                            <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Footer -->
                        <div class="card-footer bg-light d-flex justify-content-between">
                            <div>
                                <a href="{{ route('admin.clients.index') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-times me-1"></i>إلغاء
                                </a>
                            </div>
                            <div>
                                <button type="reset" class="btn btn-outline-warning me-2">
                                    <i class="fas fa-redo me-1"></i>مسح النموذج
                                </button>
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-save me-1"></i>إنشاء العميل
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @endsection

    @section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
        // Auto-calculate end date when start date changes
        const startDateInput = document.getElementById('subscription_start_date');
        const endDateInput = document.getElementById('subscription_end_date');
        const billingCycleSelect = document.getElementById('billing_cycle');

        function updateEndDate() {
            if (startDateInput.value && billingCycleSelect.value) {
                const startDate = new Date(startDateInput.value);
                const endDate = new Date(startDate);

                switch(billingCycleSelect.value) {
                    case 'monthly':
                        endDate.setMonth(endDate.getMonth() + 1);
                        break;
                    case 'quarterly':
                        endDate.setMonth(endDate.getMonth() + 3);
                        break;
                    case 'yearly':
                        endDate.setFullYear(endDate.getFullYear() + 1);
                        break;
                }

                endDateInput.value = endDate.toISOString().split('T')[0];
            }
        }

        startDateInput?.addEventListener('change', updateEndDate);
        billingCycleSelect?.addEventListener('change', updateEndDate);

        // Form validation
        const form = document.querySelector('form');
        form?.addEventListener('submit', function(e) {
            if (!form.checkValidity()) {
                e.preventDefault();
                e.stopPropagation();
            }
            form.classList.add('was-validated');
        });
    });
    </script>
    @endsection