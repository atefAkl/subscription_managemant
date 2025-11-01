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
            <h2 class="mb-1">إدارة الباقات</h2>
            <p class="text-muted mb-0">إدارة شاملة للباقات والخدمات المقدمة من الادارة للعملاء</p>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#createFeatureModal">
                <i class="fas fa-filter"></i> إضافة ميزة
            </button>
            <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#createPackageModal">
                <i class="fas fa-filter"></i> إضافة باقة
            </button>
            <button class="btn btn-success" onclick="exportSubscriptions()">
                <i class="fas fa-download"></i> تصدير
            </button>
        </div>
    </div>
    <div class="grid grid-cols-4">
        @forelse ($packages ?? [] as $package)
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title">{{ $package->name }}</h5>
                <div>
                    <p class="card-text">{{ $package->price }} / {{ $package->duration }} {{ $package->duration_unit}}</p>

                </div>
            </div>
            <div class="card-body">
                <p class="card-text">{{ $package->description }}</p>
                <p class="card-text">{{ $package->status }}</p>
                <button class="btn btn-primary customize-package-features" data-package_id="{{ $package->id }}" data-bs-toggle="modal"
                    data-bs-target="#customizePackageFeaturesModal">تخصيص الميزات للباقة</button>
            </div>
            <div class="card-footer">
                <p class="card-text">أضيفت فى: {{ $package->created_at->format('Y-m-d H:i:s') }}</p>

                <p class="card-text">تم التحديث فى: {{ $package->updated_at->format('Y-m-d H:i:s') }}</p>
            </div>
        </div>
        @empty
        <div class="alert alert-info">لا يوجد باقات</div>
        @endforelse
    </div>

    {{-- Modals --}}
    {{-- Create Feature Modal --}}
    <div class="modal fade" id="createFeatureModal" tabindex="-1" aria-labelledby="createFeatureModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createFeatureModalLabel">إضافة ميزة</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('admin.packages.features.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="name" class="form-label">اسم الميزة</label>
                                    <input type="text" class="form-control" id="name" name="name" placeholder="اسم الميزة" value="{{ old('name') }}">
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
                        <button type="submit" class="btn btn-primary">حفظ</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    {{-- Create Package Modal --}}
    <div class="modal fade" id="createPackageModal" tabindex="-1" aria-labelledby="createPackageModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createPackageModalLabel">إضافة باقة</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('admin.packages.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="name" class="form-label">اسم الباقه</label>
                                    <input type="text" class="form-control" id="name" name="name" placeholder="اسم الباقه" value="{{ old('name') }}">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="description" class="form-label">وصف الباقه</label>
                                    <input type="text" class="form-control" id="description" name="description" placeholder="" value="{{ old('description') }}">
                                </div>
                            </div>
                            <div class="col-md-12 grid grid-cols-1 md:grid-cols-3 gap-2">
                                <div class="mb-3">
                                    <label for="price" class="form-label">السعر</label>
                                    <input type="text" class="form-control" id="price" name="price" placeholder="" value="{{ old('price') }}">
                                </div>

                                <div class="mb-3">
                                    <label for="duration" class="form-label">الدورة</label>
                                    <select class="form-select" name="duration_unit" id="">
                                        <option {{ old('duration_type') == 'days' ? 'selected' : ''}} value="days">اليوم</option>
                                        <option {{ old('duration_type') == 'weeks' ? 'selected' : ''}} value="weeks">الاسبوع</option>
                                        <option {{ old('duration_type') == 'months' ? 'selected' : ''}} value="months">الشهر</option>
                                        <option {{ old('duration_type') == 'years' ? 'selected' : ''}} value="years">السنة</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="duration" class="form-label">المدة</label>
                                    <input type="text" class="form-control" id="duration" name="duration" placeholder="" value="{{ old('duration') }}">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
                        <button type="submit" class="btn btn-primary">حفظ</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="customizePackageFeaturesModal" tabindex="-1" aria-labelledby="customizePackageFeaturesModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="customizePackageFeaturesModalLabel">تخصيص الميزات للباقة</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form data-action="" action="" method="POST">

                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="name" class="form-label">اسم الباقه</label>
                                    <input type="text" class="form-control" id="name" name="name" placeholder="اسم الباقه" value="{{ old('name') }}">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="description" class="form-label">وصف الباقه</label>
                                    <input type="text" class="form-control" id="description" name="description" placeholder="" value="{{ old('description') }}">
                                </div>
                            </div>
                            <div class="col-md-12 grid grid-cols-1 md:grid-cols-3 gap-2">
                                <div class="mb-3">
                                    <label for="price" class="form-label">السعر</label>
                                    <input type="text" class="form-control" id="price" name="price" placeholder="" value="{{ old('price') }}">
                                </div>

                                <div class="mb-3">
                                    <label for="duration" class="form-label">الدورة</label>
                                    <select class="form-select" name="duration_unit" id="">
                                        <option {{ old('duration_type') == 'days' ? 'selected' : ''}} value="days">اليوم</option>
                                        <option {{ old('duration_type') == 'weeks' ? 'selected' : ''}} value="weeks">الاسبوع</option>
                                        <option {{ old('duration_type') == 'months' ? 'selected' : ''}} value="months">الشهر</option>
                                        <option {{ old('duration_type') == 'years' ? 'selected' : ''}} value="years">السنة</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="duration" class="form-label">المدة</label>
                                    <input type="text" class="form-control" id="duration" name="duration" placeholder="" value="{{ old('duration') }}">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
                        <button type="submit" class="btn btn-primary">حفظ</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).on('click', '.customize-package-features', function () {
        let form = $('#customizePackageFeaturesModal form');
        let action = "{{ route('admin.packages.customizeFeatures', 'packageIdVariable') }}";
        
        let packageId = $(this).data('package_id');
        console.log(action);
        console.log( form, packageId, action);
        form.attr('action', action.replace('packageIdVariable', packageId));
        console.log( form, packageId, form.attr('action'));
        
    })
</script>
@endsection