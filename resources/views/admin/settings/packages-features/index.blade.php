@extends('layouts.app')

@section('content')
<style>
    #listView,
    #gridView {
        display: none;
    }

    #listView.active,
    #gridView.active {
        display: block;
    }
</style>
<x-breadcrumb :items="[
        ['label' => 'لوحة الإدارة', 'url' => route('admin.dashboard')],
        ['label' => 'الإعدادات', 'url' => route('admin.settings.index')],
        ['label' => 'الميزات', 'url' => ''],
    ]" />
{{-- ============================================ --}}

<h3 class="text-2xl font-bold mb-6">إدارة الميزات &nbsp; &nbsp;
    <button data-bs-toggle="modal" data-bs-target="#addNewGroup" class="btn btn-outline-primary btn-sm"><i class="fa fa-plus"></i> اضافة ميزه جديدة</button>
</h3>
<div class="modal fade" id="addNewGroup">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">إضافة ميزه جديدة</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">

                <form class="pt-1" action="{{ route('admin.settings.packages.features.store') }}" method="POST">
                    @csrf
                    <div class="input-group py-1">
                        <label class="input-group-text" for="name">اسم الميزه</label>
                        <input type="text" name="name" id="name" class="form-control" required>
                    </div>
                    <div class="form-floating py-1">
                        <textarea name="description" id="description" class="form-control" required></textarea>
                        <label for="description">وصف الميزه</label>
                    </div>

                    <button type="submit" class="input-group-text text-primary"><i class="fa-solid fa-paper-plane"></i> &nbsp; اضافة ميزة</button>
                </form>
            </div>
        </div>
    </div>
</div>
{{-- Icons to change display view from grid to list --}}
<div class="input-group d-flex justify-content-end mb-2">

    <button id="gridViewBtn" class="input-group-text btn btn-outline-primary btn-sm active"><i class="fa fa-th"></i></button>
    <button id="listViewBtn" class="input-group-text btn btn-outline-primary btn-sm"><i class="fa fa-list"></i></button>

</div>
{{-- Grid view --}}
<div id="gridView" class="active">
    <div class="row align-items-stretch">
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
        @forelse ($features as $f)
        <div class="col col-md-6 d-flex col-lg-4 col-xl-3 device-item mt-1 p-1">
            <div class="card relative w-100 m-0" style="overflow: hidden;">
                <div class="card-body text-center pt-3 pb-5">

                    <h3 class="card-title text-center fw-bold py-2">{{$f->display_order}} - {{ $f->name }}</h3>
                    <p class="card-text">{{ $f->description }}</p>
                </div>
                <form action="{{route('admin.settings.packages.features.destroy')}}" method="POST" class="btns absolute d-flex justify-content-center w-100 gap-2">
                    @csrf
                    @method('DELETE')
                    <input type="hidden" name="id" value="{{$f->id}}">
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
</div>
{{-- List view --}}
<div id="listView" class="">
    <ul class="list-group">

        <style>
            ul.list-group li.list-group-item {
                overflow: hidden;
            }

            ul.list-group li.list-group-item form.btns {
                left: -10rem;
                bottom: 1rem;
            }

            ul.list-group li.list-group-item:hover form.btns {
                left: 2rem;
            }
        </style>
        @forelse ($features as $f)
        <li class="list-group-item relative">
            <div class="d-flex flex-column">
                <h3 class="col col-auto">{{ $f->display_order }} - {{ $f->name }}</h3>
                <small class="col col-auto px-4 pt-2">{{ $f->description }}</small>
            </div>
            <form action="{{route('admin.settings.packages.features.destroy')}}" method="delete" class="btns absolute d-flex justify-content-center
        gap-2">
                @csrf
                @method('DELETE')
                <input type="hidden" name="id" value="{{$f->id}}">
                <button type="button" class="btn btn-outline-primary btn-sm"><i class="fa fa-edit"></i></button>
                <button type="submit" class="btn btn-outline-danger btn-sm"><i class="fa fa-trash"></i></button>
            </form>
        </li>
        @empty
        <div class="col col-md-12">
            <div class="alert alert-info">
                <h4>لا يوجد أنواع</h4>
            </div>
        </div>
        @endforelse
    </ul>
</div>
<script>
    $(document).ready(function() {
        [$('#gridViewBtn'), $('#listViewBtn')].forEach( function(el) {
            el.on('click', function() {
                [$('#listView'), $('#gridView'), $('#gridViewBtn'), $('#listViewBtn')].forEach( function(part) {
                    part.toggleClass('active');
                });
            });
        });
    });

</script>
@endsection