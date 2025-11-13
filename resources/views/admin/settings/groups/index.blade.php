@extends('layouts.app')
@section('content')
<x-breadcrumb :items="[
        ['label' => 'لوحة الإدارة', 'url' => route('admin.dashboard')],
        ['label' => 'الإعدادات', 'url' => route('admin.settings.index')],
        ['label' => 'إدارة المجموعات', 'url' => ''],
    ]" />


<h3 class="text-2xl font-bold mb-6">إدارة المجموعات &nbsp; &nbsp;
    <button data-bs-toggle="modal" data-bs-target="#addNewGroup" class="btn btn-outline-primary btn-sm"><i
            class="fa fa-plus"></i> اضافة
        محموعة جديدة</button>
</h3>
<div class="modal fade" id="addNewGroup">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">إضافة مجموعة جديدة</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">

                <form class="pt-1" action="{{ route('admin.settings.groups.store') }}" method="POST">
                    @csrf

                    <div class="input-group py-1">
                        <label class="input-group-text" for="name">اسم المجموعة</label>
                        <input type="text" name="name" class="form-control" value="" required
                            id="name">
                    </div>
                    <div class="form-floating py-1">
                        <label class="form-label">وصف المجموعة</label>
                        <textarea class="form-control" placeholder="Description" type="text" name="description" id="description"></textarea>
                    </div>

                    <button type="submit" class="input-group-text text-primary mt-2 btn-block"><i
                            class="fa-solid fa-paper-plane"></i> &nbsp; اضافة بيانات
                        المجموعة الجديدة</button>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="grid grid-cols-1">
    @foreach ($groups as $group)
    <div class="bg-white p-4 rounded-lg shadow-md mb-3">
        <h4 class="text-lg font-bold">{{ $group->name }}</h4>
        <p class="text-gray-600">{{ $group->description }}</p>
        {{-- display group items in collapse component --}}

        <button
            data-group-id="{{ $group->id }}"
            data-group-name="{{ $group->name }}"
            data-group-description="{{ $group->description }}"
            class="edit-group-button btn btn-outline-primary btn-sm" data-bs-toggle="modal"
            data-bs-target="#editGroupModal"><i class="fa fa-edit"></i> تعديل
        </button>
        <a href="{{ route('admin.settings.groups.destroy', $group->id) }}" class="btn btn-outline-danger btn-sm">
            <i class="fa fa-trash"></i> حذف
        </a>
        <button data-group-id="{{ $group->id }}" data-group-name="{{ $group->name }}"
            class="add-group-item-button btn btn-outline-success btn-sm" data-bs-toggle="modal"
            data-bs-target="#addGroupItemModal"><i class="fa fa-plus-circle"></i> إضافة عنصر للمجموعة
        </button>

        <div class="mb-0 mt-3">

            @forelse ($group->group_items as $gItem)
            <form class="pt-1" action="{{ route('admin.settings.groups.items.update', $gItem->id) }}"
                method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" name="group_id" value="{{ $group->id }}">

                <div class="input-group py-0">
                    <label class="input-group-text" for="name" title="اسم عنصر المجموعة"><i
                            class="fa fa-address-card"></i></label>
                    <input type="text" name="name" class="form-control" value="{{ $gItem->name }}"
                        required id="name">
                    <label class="input-group-text" for="description" title="وصف عنصر المجموعة"><i
                            class="fa-solid fa-file-lines"></i></label>
                    <input type="text" name="description" class="form-control" value="{{ $gItem->description }}"
                        required id="description">
                    <button type="submit" class="input-group-text text-primary"><i
                            class="fa fa-edit"></i></button>
                    <a href="{{route('admin.settings.groups.items.delete', ['groupItem' => $gItem->id])}}" class="input-group-text text-danger"><i class="fa fa-trash"></i>
                    </a>
                </div>
            </form>
            @empty
            <li class="list-group-item ">No items Added</li>
            @endforelse
            </ul>
        </div>
    </div>
    @endforeach

    <!-- Add Group Item Modal -->
    <div class="modal fade" id="addGroupItemModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">إضافة عنصر للمجموعة</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">

                    <form class="pt-1" action="{{ route('admin.settings.groups.items.store') }}" method="POST">
                        @csrf
                        <input type="hidden" id="group_id" name="group_id" value="">
                        <h4 id="group_name">اسم المجموعة: <span></span></h4>
                        <div class="input-group py-1">
                            <label class="input-group-text" for="name">اسم العنصر</label>
                            <input type="text" name="name" class="form-control" value="" required
                                id="name">
                        </div>
                        <div class="form-floating py-1">
                            <label class="form-label">وصف العنصر</label>
                            <textarea class="form-control" placeholder="Description" type="text" name="description" id="description"></textarea>
                        </div>

                        <button type="submit" class="input-group-text text-primary mt-2 btn-block"><i
                                class="fa-solid fa-paper-plane"></i> &nbsp; اضافة بيانات
                            العنصر الجديد</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Group Modal -->
    <div class="modal fade" id="editGroupModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">تعديل المجموعة</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">

                    <form class="pt-1" action="{{ route('admin.settings.groups.update') }}" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" id="edit-group-id" name="group_id" value="">

                        <div class="input-group py-1">
                            <label class="input-group-text" for="edit-group-name">اسم المجموعة</label>
                            <input type="text" name="name" class="form-control" value="" required
                                id="edit-group-name">
                        </div>
                        <div class="form-floating py-1">
                            <label class="form-label">وصف المجموعة</label>
                            <textarea class="form-control" placeholder="Description" type="text" name="description" id="edit-group-description"></textarea>
                        </div>

                        <button type="submit" class="input-group-text text-primary mt-2 btn-block"><i
                                class="fa-solid fa-paper-plane"></i> &nbsp; تحديث بيانات
                            المجموعة</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            $(document).on('click', '.add-group-item-button', function() {
                const groupId = $(this).attr('data-group-id');
                const groupName = $(this).attr('data-group-name');
                $('#group_id').val(groupId);
                $('#group_name span').html(groupName);
            });
            $(document).on('click', '.edit-group-button', function() {
                const groupId = $(this).attr('data-group-id');
                const groupName = $(this).attr('data-group-name');
                const groupDescription = $(this).attr('data-group-description');
                $('#edit-group-id').val(groupId);
                $('#edit-group-name').val(groupName);
                $('#edit-group-description').val(groupDescription);
            });
        });
    </script>
</div>
@endsection