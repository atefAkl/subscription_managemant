@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">طلب اشتراك للباقة: {{ $package->name }}</h2>

    <div class="card mb-4">
        <div class="card-header">
            <h5 class="card-title mb-0">معلومات الباقة</h5>
        </div>
        <div class="card-body">
            <p><strong>السعر:</strong> {{ $package->price }} / {{ $package->duration }} {{ $package->duration_unit }}</p>
            <p><strong>الوصف:</strong> {{ $package->description }}</p>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">بيانات طلب الاشتراك</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('client.subscriptions.store-from-package', $package->id) }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="mb-3">
                    <label for="certificate_number" class="form-label">رقم شهادة Apple Developer</label>
                    <input type="text" name="certificate_number" id="certificate_number" class="form-control" value="{{ old('certificate_number') }}" required>
                </div>

                <div class="mb-3">
                    <label for="certificate_file" class="form-label">ملف الشهادة / صورة منها (اختياري)</label>
                    <input type="file" name="certificate_file" id="certificate_file" class="form-control" accept=".jpg,.jpeg,.png,.pdf">
                </div>

                <div class="mb-3">
                    <label for="payment_receipt" class="form-label">صورة إيصال التحويل</label>
                    <input type="file" name="payment_receipt" id="payment_receipt" class="form-control" accept=".jpg,.jpeg,.png,.pdf" required>
                </div>

                <div class="mb-3">
                    <label for="notes" class="form-label">ملاحظات إضافية (اختياري)</label>
                    <textarea name="notes" id="notes" rows="3" class="form-control">{{ old('notes') }}</textarea>
                </div>

                <button type="submit" class="btn btn-primary">إرسال الطلب</button>
                <a href="{{ route('client.subscriptions') }}" class="btn btn-secondary">إلغاء</a>
            </form>
        </div>
    </div>
</div>
@endsection