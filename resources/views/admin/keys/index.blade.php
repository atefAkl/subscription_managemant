@extends('layouts.app')
@section('content')
<x-breadcrumb :items="[
        ['label' => 'لوحة الإدارة', 'url' => route('admin.dashboard')],
        ['label' => 'الإعدادات', 'url' => route('admin.settings.index')],
        ['label' => 'إدارة المجموعات', 'url' => ''],
    ]" />

<h3 class="text-2xl font-bold mb-6">إدارة المفاتيح &nbsp; &nbsp;
    <button data-bs-toggle="modal" data-bs-target="#addNewGroup" class="btn btn-outline-primary btn-sm"><i class="fa fa-plus"></i> اضافة مجموعة مفاتيح جديدة</button>
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
                        <input type="number" step="1" min="1" name="keysNum" class="form-control" value="" required id="keysNum">
                    </div>

                    <button type="submit" class="input-group-text text-primary"><i class="fa-solid fa-paper-plane"></i> &nbsp; توليد المفاتيح</button>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="grid grid-cols-1">
    <form>
        <div class="input-group py-1">
            <label class="input-group-text" for="status">الحالة</label>
            <select name="status" id="status" class="form-select">
                <option hidden>الكل</option>
                <option value="active">نشط</option>
                <option value="new">جديد</option>
                <option value="blocked">مغلق</option>
                <option value="expired">منتهٔ</option>
            </select>

            <label class="input-group-text" for="device_type_id">نوع الجهاز</label>
            <select name="device_type_id" id="device_type_id" class="form-select">
                <option hidden>الكل</option>
                @foreach ($devices as $device)
                <option value="{{$device->id}}">{{$device->model}} - {{$device->device_type}}</option>
                @endforeach
            </select>

            <label class="input-group-text" for="group_item_id">المجموعة</label>
            <select name="group_item_id" id="group_item_id" class="form-select">
                <option hidden>الكل</option>
                @foreach ($groups as $group)
                <option value="{{$group->id}}">{{$group->group->name}} - {{$group->name}}</option>
                @endforeach
            </select>
            <input type="submit" class="input-group-text text-primary" value="بحث">
        </div>
    </form>
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
                <td><span style="width: 80px; display: inline-block; overflow: hidden; text-overflow: ellipsis;" title="{{$key->key_string}}">{{$key->key_string}}</span></td>
                <td>{{$key->group_item ? $key->groupItem->name: 'N/A'}}</td>
                <td>{{$key->status}}</td>
                <td>{{@$key->client->user_name ?? 'N/A'}}</td>
                <td>{{$key->uuid ?? 'N/A'}}</td>
                <td>{{Carbon\Carbon::parse($key->activated_at)->format('Y-m-d') ?? 'N/A'}}</td>
                <td>{{$key->remainingDays() ?? 'N/A'}}</td>
                <td>
                    <button data-group-id="{{ $key->id }}" data-key-name="{{ $key->key_string }}" class="edit-key-button btn btn-outline-primary btn-sm" data-bs-toggle="modal"
                        data-bs-target="#editkeyModal"><i class="fa fa-edit"></i> تعديل
                    </button>
                    <a href="{{ route('admin.keys.destroy', ['key' => $key->id]) }}" class="btn btn-outline-danger btn-sm">
                        <i class="fa fa-trash"></i> حذف
                    </a>
                    <a data-url="{{ route('admin.keys.activate') }}" data-bs-toggle="modal" data-bs-target="#activateKeyModal" data-key-id="{{$key->id}}"
                        data-key-string="{{$key->key_string}}" class="btn btn-outline-success btn-sm activate-key">
                        <i class="fa fa-check"></i>
                    </a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

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

                        <div id="keyString">المفتاح: <span></span></div>
                        <div class="input-group py-1">
                            <label class="input-group-text" for="edit-group-name">{{__('العميل')}}</label>
                            <input type="text" class="form-control" value="" id="edit-group-name">
                            <select name="user_id" class="form-control" required id="user_id">
                                @forelse($clients as $client)
                                <option value="{{$client->id}}">{{$client->name}}</option>
                                @empty
                                <option value="">No Active Clients</option>
                                @endforelse
                            </select>
                        </div>
                        <div class="input-group py-1">
                            <label class="input-group-text" for="device_type_id">{{__('نوع الجهاز')}}</label>
                            <select name="device_type_id" class="form-control" required id="device_type_id">
                                @forelse($devices as $device)
                                <option value="{{$device->id}}">{{$device->model}} - {{$device->device_type}}</option>
                                @empty
                                <option value="">No Active Devices</option>
                                @endforelse
                            </select>
                        </div>

                        <div class="input-group py-1">
                            <label class="input-group-text" for="group_item_id">اسم المجموعة</label>
                            <select name="group_item_id" class="form-control" required id="group_item_id">
                                @forelse($groups as $group)
                                <option value="{{$group->id}}">{{$group->group->name}} - {{$group->name}}</option>
                                @empty
                                <option value="">No Active Devices</option>
                                @endforelse
                            </select>
                        </div>

                        <div class="input-group py-1">
                            <label class="input-group-text" for="uuid">{{__('رمز الجهاز UUID')}}</label>
                            <input type="text" length="10"
                                onkeyup="this.value = this.value.replace(/[^A-Z0-9]/g, ''); if(this.value.length > 10) this.value = this.value.slice(0, 10);" name="uuid"
                                class="form-control" required id="uuid">
                        </div>


                        <button type="submit" class="btn btn-primary" id="activateKey">{{__('تفعيل المفتاح')}}</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{__('لا')}}</button>
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
            $(document).on('click', '.activate-key', function() {
                const action = $(this).attr('data-url');
                const keyId = $(this).attr('data-key-id');
                const keyString = $(this).attr('data-key-string');
                $('#activateKeyModal form').attr('action', action);
                $('#key_id').val(keyId);
                $('#keyString span').html(keyString);
                $('#activateKeyModal').modal('show');
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