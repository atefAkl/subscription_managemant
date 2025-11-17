@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">طلب اشتراك للباقة: {{ $package->name }}</h2>

    <div class="card mb-4">
        <div class="card-header">
            <h4 class="card-title mb-0 py-2">معلومات الباقة</h4>
        </div>
        <div class="card-body">
            <p><strong>السعر:</strong> {{ $package->price }} / {{ $package->duration }} {{ $package->duration_unit }}</p>
            <p><strong>الوصف:</strong> {{ $package->description }}</p>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h4 class="card-title mb-0 py-2">بيانات طلب الاشتراك</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('client.subscriptions.store-from-package', $package->id) }}" method="POST" enctype="multipart/form-data">
                @csrf

                <!-- قسم وسيلة الدفع -->
                <div class="mb-4">
                    <h5 class="text-primary mb-3">
                        <i class="fas fa-credit-card me-2"></i>اختر وسيلة الدفع
                    </h5>

                    <div class="row g-3">
                        <!-- فودافون كاش -->
                        <div class="col-md-6">
                            <div class="card payment-method-card" data-method="vodafone_cash">
                                <div class="card-body text-center">
                                    <input type="radio" name="payment_method" value="vodafone_cash" id="vodafone_cash" class="form-check-input me-2"
                                        {{ old('payment_method') == 'vodafone_cash' ? 'checked' : '' }}>
                                    <label for="vodafone_cash" class="form-check-label">
                                        <div class="payment-icon mb-2">
                                            <i class="fas fa-mobile-alt text-danger" style="font-size: 2rem;"></i>
                                        </div>
                                        <strong>فودافون كاش</strong>
                                        <small class="d-block text-muted">Vodafone Cash</small>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- اتصالات كاش -->
                        <div class="col-md-6">
                            <div class="card payment-method-card" data-method="etisalat_cash">
                                <div class="card-body text-center">
                                    <input type="radio" name="payment_method" value="etisalat_cash" id="etisalat_cash" class="form-check-input me-2"
                                        {{ old('payment_method') == 'etisalat_cash' ? 'checked' : '' }}>
                                    <label for="etisalat_cash" class="form-check-label">
                                        <div class="payment-icon mb-2">
                                            <i class="fas fa-mobile-alt text-warning" style="font-size: 2rem;"></i>
                                        </div>
                                        <strong>اتصالات كاش</strong>
                                        <small class="d-block text-muted">Etisalat Cash</small>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- أورانج كاش -->
                        <div class="col-md-6">
                            <div class="card payment-method-card" data-method="orange_cash">
                                <div class="card-body text-center">
                                    <input type="radio" name="payment_method" value="orange_cash" id="orange_cash" class="form-check-input me-2"
                                        {{ old('payment_method') == 'orange_cash' ? 'checked' : '' }}>
                                    <label for="orange_cash" class="form-check-label">
                                        <div class="payment-icon mb-2">
                                            <i class="fas fa-mobile-alt text-warning" style="font-size: 2rem;"></i>
                                        </div>
                                        <strong>أورانج كاش</strong>
                                        <small class="d-block text-muted">Orange Cash</small>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- فوري -->
                        <div class="col-md-6">
                            <div class="card payment-method-card" data-method="fawry">
                                <div class="card-body text-center">
                                    <input type="radio" name="payment_method" value="fawry" id="fawry" class="form-check-input me-2"
                                        {{ old('payment_method') == 'fawry' ? 'checked' : '' }}>
                                    <label for="fawry" class="form-check-label">
                                        <div class="payment-icon mb-2">
                                            <i class="fas fa-store text-success" style="font-size: 2rem;"></i>
                                        </div>
                                        <strong>فوري</strong>
                                        <small class="d-block text-muted">Fawry</small>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- التحويل البنكي -->
                        <div class="col-md-6">
                            <div class="card payment-method-card" data-method="bank_transfer">
                                <div class="card-body text-center">
                                    <input type="radio" name="payment_method" value="bank_transfer" id="bank_transfer" class="form-check-input me-2"
                                        {{ old('payment_method') == 'bank_transfer' ? 'checked' : '' }}>
                                    <label for="bank_transfer" class="form-check-label">
                                        <div class="payment-icon mb-2">
                                            <i class="fas fa-university text-primary" style="font-size: 2rem;"></i>
                                        </div>
                                        <strong>تحويل بنكي</strong>
                                        <small class="d-block text-muted">Bank Transfer</small>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- فيزا كارد -->
                        <div class="col-md-6">
                            <div class="card payment-method-card" data-method="visa_card">
                                <div class="card-body text-center">
                                    <input type="radio" name="payment_method" value="visa_card" id="visa_card" class="form-check-input me-2"
                                        {{ old('payment_method') == 'visa_card' ? 'checked' : '' }}>
                                    <label for="visa_card" class="form-check-label">
                                        <div class="payment-icon mb-2">
                                            <i class="fas fa-credit-card text-info" style="font-size: 2rem;"></i>
                                        </div>
                                        <strong>فيزا كارد</strong>
                                        <small class="d-block text-muted">Visa Card</small>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    @error('payment_method')
                    <div class="text-danger mt-2">
                        <small><i class="fas fa-exclamation-circle me-1"></i>{{ $message }}</small>
                    </div>
                    @enderror
                </div>

                <!-- رفع إيصال الدفع (يظهر للوسائل الخارجية) -->
                <div id="receipt-upload-section" class="mb-3" style="display: none;">
                    <label for="payment_receipt" class="form-label">
                        <i class="fas fa-upload me-2"></i>صورة إيصال التحويل *
                    </label>
                    <input type="file" name="payment_receipt" id="payment_receipt" class="form-control" accept=".jpg,.jpeg,.png,.pdf">
                    <small class="text-muted">يُرجى رفع صورة واضحة لإيصال التحويل (JPG, PNG, PDF)</small>
                    @error('payment_receipt')
                    <div class="text-danger mt-1">
                        <small><i class="fas fa-exclamation-circle me-1"></i>{{ $message }}</small>
                    </div>
                    @enderror
                </div>

                <!-- رسالة البوابات البنكية -->
                <div id="gateway-maintenance-message" class="alert alert-warning" style="display: none;">
                    <i class="fas fa-tools me-2"></i>
                    <strong>جار إصلاح البوابة</strong><br>
                    يرجى اختيار أحد الطرق المتاحة حالياً (المحافظ الإلكترونية أو فوري)
                </div>

                <hr class="my-4">

                <div class="mb-3">
                    <label for="notes" class="form-label">ملاحظات إضافية (اختياري)</label>
                    <textarea name="notes" id="notes" rows="3" class="form-control" placeholder="أي ملاحظات تود إضافتها...">{{ old('notes') }}</textarea>
                </div>

                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <a href="{{ route('client.subscriptions') }}" class="btn btn-secondary me-md-2">
                        <i class="fas fa-times me-2"></i>إلغاء
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-paper-plane me-2"></i>إرسال الطلب
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    $(document).on('click', 'input[name="payment_method"]', function() {
    console.log('document clicked');
    console.log($(this).val());
});
document.addEventListener('DOMContentLoaded', function() {
    const paymentMethods = document.querySelectorAll('input[name="payment_method"]');
    const receiptSection = document.getElementById('receipt-upload-section');
    const maintenanceMessage = document.getElementById('gateway-maintenance-message');
    const paymentCards = document.querySelectorAll('.payment-method-card');


    // وسائل الدفع التي تتطلب رفع إيصال
    const receiptRequiredMethods = ['vodafone_cash', 'etisalat_cash', 'orange_cash', 'fawry'];
    
    // وسائل الدفع المعطلة مؤقتاً
    const maintenanceMethods = ['bank_transfer', 'visa_card'];

    function updatePaymentUI() {
        const selectedMethod = document.querySelector('input[name="payment_method"]:checked');
        
        // إخفاء جميع الأقسام أولاً
        receiptSection.style.display = 'none';
        maintenanceMessage.style.display = 'none';
        
        // إزالة التحديد من جميع الكروت
        paymentCards.forEach(card => card.classList.remove('selected'));
        
        if (selectedMethod) {
            const selectedCard = selectedMethod.closest('.payment-method-card');
            selectedCard.classList.add('selected');
            
            const method = selectedMethod.value;
            
            if (receiptRequiredMethods.includes(method)) {
                receiptSection.style.display = 'block';
                document.getElementById('payment_receipt').setAttribute('required', 'required');
            } else {
                document.getElementById('payment_receipt').removeAttribute('required');
            }
            
            if (maintenanceMethods.includes(method)) {
                maintenanceMessage.style.display = 'block';
            }
        }
    }

    // إضافة مستمعي الأحداث
    paymentMethods.forEach(method => {
        method.addEventListener('change', updatePaymentUI);
    });

    // جعل الكروت قابلة للنقر
    paymentCards.forEach(card => {
        card.addEventListener('click', function() {
            const radio = this.querySelector('input[type="radio"]');
            radio.checked = true;
            updatePaymentUI();
        });
    });

    // تحديث الواجهة عند التحميل (للقيم المحفوظة)
    updatePaymentUI();

    // التحقق من صحة النموذج قبل الإرسال
    document.querySelector('form').addEventListener('submit', function(e) {
        const selectedMethod = document.querySelector('input[name="payment_method"]:checked');
        
        if (!selectedMethod) {
            e.preventDefault();
            alert('يرجى اختيار وسيلة دفع');
            return false;
        }
        
        if (maintenanceMethods.includes(selectedMethod.value)) {
            e.preventDefault();
            alert('هذه الوسيلة غير متاحة حالياً، يرجى اختيار وسيلة أخرى');
            return false;
        }
        
        if (receiptRequiredMethods.includes(selectedMethod.value)) {
            const receiptFile = document.getElementById('payment_receipt').files[0];
            if (!receiptFile) {
                e.preventDefault();
                alert('يرجى رفع صورة إيصال التحويل');
                return false;
            }
        }
    });
});
</script>
@endsection

@section('styles')
<style>
    .payment-method-card {
        cursor: pointer;
        transition: all 0.3s ease;
        border: 2px solid #e9ecef;
    }

    .payment-method-card:hover {
        border-color: #007bff;
        box-shadow: 0 4px 8px rgba(0, 123, 255, 0.1);
    }

    .payment-method-card.selected {
        border-color: #007bff;
        background-color: #f8f9ff;
        box-shadow: 0 4px 12px rgba(0, 123, 255, 0.15);
    }

    .payment-method-card input[type="radio"] {
        display: none;
    }

    .payment-method-card label {
        cursor: pointer;
        width: 100%;
        margin: 0;
    }

    .payment-icon {
        transition: transform 0.2s ease;
    }

    .payment-method-card:hover .payment-icon {
        transform: scale(1.1);
    }
</style>

@endsection

@section('scripts')
@endsection