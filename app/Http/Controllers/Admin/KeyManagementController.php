<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DeviceType;
use App\Models\GroupItem;
use App\Models\Key;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;

class KeyManagementController extends Controller
{
    /**
     * عرض صفحة إدارة المفاتيح
     */
    public function index()
    {
        $keys = Key::all();
        $devices = DeviceType::all();
        $clients = User::where(['role' => 'client', 'status' => 'active'])->get();
        $groups = GroupItem::where([])->with('group')->get();

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

    public function activate(Request $re, Key $key)
    {

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
