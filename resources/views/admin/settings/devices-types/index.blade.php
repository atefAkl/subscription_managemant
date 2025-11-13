@extends('layouts.app')
@section('content')
<x-breadcrumb :items="[
        ['label' => 'لوحة الإدارة', 'url' => route('admin.dashboard')],
        ['label' => 'الإعدادات', 'url' => route('admin.settings.index')],
        ['label' => 'إدارة أنواع الأجهزة', 'url' => ''],
    ]" />


<h3 class="text-2xl font-bold mb-6">إدارة أنواع الأجهزة &nbsp; &nbsp;
    <button data-bs-toggle="modal" data-bs-target="#addNewGroup" class="btn btn-outline-primary btn-sm"><i
            class="fa fa-plus"></i> اضافة
        نوع جديد</button>
</h3>

<div class="row">
    <style>
        .device-item .card>.btns {
            bottom: -50px;
            transition: all .3s ease-in-out;
            text-align: center;
        }

        .device-item .card:hover>.btns {
            bottom: 10px;
        }
    </style>
    @forelse ($devices as $d)
    <div class="col col-md-6 col-lg-4 col-xl-3 device-item mt-3">
        <div class="card relative" style="overflow: hidden;">
            <div class="card-body text-center pt-3 pb-5">
                <i class="{{ $d->device_icon() }}"></i>
                <h3 class="card-title text-center fw-bold py-2">{{ $d->model }}</h3>
                <p class="card-text">{{ $d->device_type }}</p>
            </div>
            <form action="{{route('admin.settings.devices.types.destroy')}}" method="POST" class="btns absolute d-flex justify-content-center w-100 gap-2">
                @csrf
                @method('DELETE')
                <input type="hidden" name="id" value="{{$d->id}}">
                <button type="button" class="btn btn-outline-primary btn-sm"><i class="fa fa-edit"></i></button>
                <button type="submit" class="btn btn-outline-danger btn-sm"><i class="fa fa-trash"></i></button>
            </form>
        </div>
    </div>
    @empty
    <div class="col col-md-12">
        <div class="alert alert-info">
            <h4>لا يوجد أنواع</h4>
        </div>
    </div>
    @endforelse
</div>
<!-- Add new device type modal -->
<div class="modal fade" id="addNewGroup">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">إضافة نوع جديد</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">

                <form class="pt-1" action="{{ route('admin.settings.devices.types.store') }}" method="POST">
                    @csrf

                    <div class="input-group py-1">
                        <label class="input-group-text" for="model">اسم الجهاز</label>
                        <input type="text" name="model" class="form-control" value="" required
                            id="model">
                    </div>

                    <div class="input-group py-1">
                        <label class="input-group-text" for="device_type">الفئة</label>
                        <select class="form-control" name="device_type" id="device_type">
                            <option {{ old('device_type') == 'iPhone' ? 'selected' : '' }} value="iPhone">هاتف</option>
                            <option {{ old('device_type') == 'iPad' ? 'selected' : '' }} value="iPad">جهاز لوحي</option>
                            <option {{ old('device_type') == 'Mac' ? 'selected' : '' }} value="Mac">كمبيوتر محمول</option>
                            <option {{ old('device_type') == 'Apple Watch' ? 'selected' : '' }} value="Apple Watch">اسوارة</option>
                            <option {{ old('device_type') == 'Apple TV' ? 'selected' : '' }} value="Apple TV">جهاز عرض</option>
                        </select>
                    </div>

                    <button type="submit" class="input-group-text text-primary mt-2 btn-block"><i
                            class="fa-solid fa-paper-plane"></i> &nbsp; اضافة بيانات
                        الجهاز</button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection