<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DeviceType;
use App\Models\GroupItem;
use App\Models\Key;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;

class KeyManagementController extends Controller
{
    /**
     * عرض صفحة إدارة المفاتيح
     */
    public function index(Request $request)
    {
        // return $request->query();
        // Build query according to request query
        $query = Key::query();
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }
        if ($request->has('device_type_id')) {
            $query->where('device_type_id', $request->device_type_id);
        }
        if ($request->has('group_item_id')) {
            $query->where('group_item_id', $request->group_item_id);
        }

        $keys = $query->get();
        $devices          = DeviceType::all();
        $clients          = User::where(['role' => 'client', 'status' => 'active'])->get();
        $groups           = GroupItem::where([])->with('group')->get();

        return view('admin.keys.index', compact('keys', 'devices', 'clients', 'groups'));
    }

    public function generate(Request $req)
    {
        for ($i = 0; $i < $req->keysNum; $i++) {
            $key = Key::create([
                'key_string' => Key::generateKeyString(),
                'period' => $req->period,
            ]);
        }
        return redirect()->back()->with('success', 'تم إنشاء المفاتيح بنجاح');
    }

    public function activate(Request $request)
    {

        // return Carbon::now();
        $validated = $request->validate([
            'key_id' => 'required|exists:ss_keys,id',
            'user_id' => 'required|exists:users,id',
            'device_type_id' => 'required|exists:device_types,id',
            'group_item_id' => 'required|exists:group_items,id',
            'uuid' => 'required|string|max:10',
        ]);
        $key = Key::find($validated['key_id']);
        $key->update([
            'user_id'           => $request->user_id,
            'device_type_id'    => $request->device_type_id,
            'group_item_id'     => $request->group_item_id,
            'uuid'              => $request->uuid,
            'status'            => 'active',
            'activated_at'      => Carbon::now(),
        ]);
        return redirect()->back()->withSuccess('تم تنشيط المفتاح بنجاح');
    }

    public function destroy(Key $key)
    {
        // return $key;
        if ($key->isActive()) {
            return redirect()->back()->withError('لا يمكنك حذف مفتاح نشط');
        }
        try {
            $key->delete();
            return redirect()->back()->with('success', 'تم حذف المفتاح بنجاح');
        } catch (Exception $e) {
            return redirect()->back()->withError('حدث عطل أثناء محاولة حذف المفتاح، انظر: ' . $e->getMessage());
        }
    }
}
