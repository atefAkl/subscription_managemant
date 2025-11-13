@extends('layouts.app')
@section('content')
<x-breadcrumb :items="[
        ['label' => 'لوحة الإدارة', 'url' => route('admin.dashboard')],
        ['label' => 'الإعدادات', 'url' => route('admin.settings.index')],
        ['label' => 'إدارة المجموعات', 'url' => ''],
    ]" />

<h3 class="text-2xl font-bold mb-6">إدارة المفاتيح &nbsp; &nbsp;
    <button data-bs-toggle="modal" data-bs-target="#addNewGroup" class="btn btn-outline-primary btn-sm"><i
            class="fa fa-plus"></i> اضافة مجموعة مفاتيح جديدة</button>
</h3>
<div class="modal fade" id="addNewGroup">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">إضافة مجموعة جديدة</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">

                <form class="pt-1" action="{{ route('admin.keys.generate') }}" method="POST">
                    @csrf
                    <div class="input-group py-1">
                        <label class="input-group-text" for="name">المدة</label>
                        <select name="period" id="period" class="form-select">

                            <option value="week">اسبوع</option>
                            <option value="month">شهر</option>
                            <option value="year">سنة</option>
                        </select>
                    </div>
                    <div class="input-group py-1">
                        <label class="input-group-text" for="keysNum">عدد المفاتيح</label>
                        <input type="number" step="1" min="1" name="keysNum" class="form-control" value="" required
                            id="keysNum">
                    </div>

                    <button type="submit" class="input-group-text text-primary mt-2 btn-block"><i
                            class="fa-solid fa-paper-plane"></i> &nbsp; توليد المفاتيح</button>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="grid grid-cols-1">
    <table class="table table-hover table-striped border">
        <thead>
            <tr>
                <th>#</th>
                <th>{{__(' رمز المفتاح')}}</th>
                <th>{{__('المجموعة')}}</th>
                <th>{{__('الحالة')}}</th>
                <th>{{__('العميل')}}</th>
                <th>UUID</th>
                <th>{{__('التنشيط')}}</th>
                <th>{{__('الايام المتبقية')}}</th>
                <th>{{__('أدارة')}}</th>

            </tr>
        </thead>
        <tbody>
            @foreach ($keys as $key)
            <tr>
                <td>{{$loop->iteration}}</td>
                <td>{{$key->key_string}}</td>
                <td>{{$key->group_item ? $key->groupItem->name: 'N/A'}}</td>
                <td>{{$key->status}}</td>
                <td>{{@$key->user->name ?? 'N/A'}}</td>
                <td>{{$key->uuid ?? 'N/A'}}</td>
                <td>{{$key->activated ?? 'N/A'}}</td>
                <td>{{$key->remainingDays() ?? 'N/A'}}</td>
                <td>
                    <button
                        data-group-id="{{ $key->id }}"
                        data-key-name="{{ $key->key_string }}"
                        class="edit-key-button btn btn-outline-primary btn-sm" data-bs-toggle="modal"
                        data-bs-target="#editkeyModal"><i class="fa fa-edit"></i> تعديل
                    </button>
                    <a href="{{ route('admin.keys.destroy', ['key' => $key->id]) }}" class="btn btn-outline-danger btn-sm">
                        <i class="fa fa-trash"></i> حذف
                    </a>
                    <a
                        data-url="{{ route('admin.keys.activate', ['key' => $key->id]) }}"
                        data-bs-toggle="modal"
                        data-bs-target="#activateKeyModal"
                        data-key-id="{{$key->id}}"
                        class="btn btn-outline-success btn-sm activate-key">
                        <i class="fa fa-check"></i>
                    </a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

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
                            <label class="input-group-text" for="name">المدة</label>
                            <select name="duration" id="duration" class="form-select">

                                <option value="7">7 يوم</option>
                                <option value="30">30 يوم</option>
                                <option value="365">365 يوم</option>
                            </select>
                        </div>
                        <div class="input-group py-1">
                            <label class="input-group-text" for="name">عدد المفاتيح</label>
                            <input type="number" step="1" min="1" name="name" class="form-control" value="" required
                                id="name">
                        </div>

                        <button type="submit" class="input-group-text text-primary mt-2 btn-block"><i
                                class="fa-solid fa-paper-plane"></i> &nbsp; توليد المفاتيح</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Activate Key Modal -->
    <div class="modal fade" id="activateKeyModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">تفعيل المفتاح</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div id="activate" class="modal-body">
                    <form method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" id="key_id" name="key_id" value="">

                        <div class="input-group py-1">
                            <label class="input-group-text" for="edit-group-name">{{__('العميل')}}</label>

                            <input type="text" class="form-control" value="" required
                                id="edit-group-name">
                            <select name="user_id" class="form-control" required
                                id="edit-group-name">
                                @forelse($clients as $client)
                                <option value="{{$client->id}}">{{$client->name}}</option>
                                @empty
                                <option value="">No Active Clients</option>
                                @endforelse
                            </select>
                        </div>
                        <div class="input-group py-1">
                            <label class="input-group-text" for="edit-group-name">{{__('نوع الجهاز')}}</label>
                            <select name="user_id" class="form-control" required
                                id="edit-group-name">
                                @forelse($devices as $device)
                                <option value="{{$device->id}}">{{$device->model}} - {{$device->device_type}}</option>
                                @empty
                                <option value="">No Active Devices</option>
                                @endforelse
                            </select>
                        </div>

                        <div class="input-group py-1">
                            <label class="input-group-text" for="edit-group-name">اسم المجموعة</label>
                            <input type="text" name="name" class="form-control" value="" required
                                id="edit-group-name">
                        </div>

                        <div class="input-group py-1">
                            <label class="input-group-text" for="edit-group-name">{{__('رمز الجهاز UUID')}}</label>
                            <input type="text" name="uuid" class="form-control" required
                                id="edit-group-name">
                        </div>
                        ffffff

                        <button type="button" class="btn btn-primary" id="activateKey">{{__('تفعيل المفتاح')}}</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{__('لا')}}</button>
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