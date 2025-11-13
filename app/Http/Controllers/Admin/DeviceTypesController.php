<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DeviceType;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class DeviceTypesController extends Controller
{
    //

    public function index()
    {
        $devices = DeviceType::where([])->paginate(15);
        return view('admin.settings.devices-types.index', compact('devices'));
    }
    public function store(Request $request)
    {
        // return $request->all();
        $validated = $request->validate([
            'model' => 'required|unique:device_types,model|max:100',
            'device_type' => 'required|in:iPhone,iPad,Mac,Apple Watch,Apple TV',
        ]);
        DeviceType::create($validated);
        return redirect()->back()->with('success', 'Device Type Created Successfully');
    }

    public function destroy(Request $req)
    {
        $dt = DeviceType::find($req->id);
        if (!$dt) {
            return redirect()->back()->withError('الجهاز الذى تحاول الوصول اليه غير موجود');
        }
        try {
            $dt->delete();
            return redirect()->route('admin.settings.devices.types.index')->withSuccess('تم حذف الجهاز من قاعدة البيانات بنجاح');
        } catch (QueryException $e) {
            return redirect()->back()->withError('لم يتم حذف الجهاز بسبب: ' . $e->getMessage());
        }
    }
}
