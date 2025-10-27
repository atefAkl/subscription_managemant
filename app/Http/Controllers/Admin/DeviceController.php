<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Device;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DeviceController extends Controller
{
    /**
     * عرض قائمة الأجهزة المطلوب تفعيلها
     */
    public function pending()
    {
        $devices = Device::with(['subscription.user'])
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.devices.pending', compact('devices'));
    }

    /**
     * عرض جميع الأجهزة
     */
    public function index(Request $request)
    {
        $status = $request->get('status', 'all');

        $query = Device::with(['subscription.user'])
            ->orderBy('created_at', 'desc');

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        $devices = $query->paginate(15);

        return view('admin.devices.index', compact('devices', 'status'));
    }

    /**
     * عرض نموذج تفعيل جهاز
     */
    public function showActivationForm($id)
    {
        $device = Device::with(['subscription.user'])
            ->where('status', 'pending')
            ->findOrFail($id);

        return view('admin.devices.activate', compact('device'));
    }

    /**
     * تفعيل جهاز
     */
    public function activate(Request $request, $id)
    {
        $device = Device::where('status', 'pending')->findOrFail($id);

        $validated = $request->validate([
            'device_name' => 'required|string|max:255',
            'machine_name' => 'required|string|max:255',
            'ip_address' => 'nullable|ip',
            'additional_info' => 'nullable|string|max:1000'
        ], [
            'device_name.required' => 'اسم الجهاز مطلوب',
            'machine_name.required' => 'اسم الماكينة مطلوب',
            'ip_address.ip' => 'عنوان IP غير صحيح'
        ]);

        // إنشاء توكن فريد
        $token = $device->generateToken();

        $device->update([
            'device_name' => $validated['device_name'],
            'machine_name' => $validated['machine_name'],
            'ip_address' => $validated['ip_address'],
            'additional_info' => $validated['additional_info'],
            'token' => $token,
            'status' => 'active',
            'activated_at' => now()
        ]);

        return redirect()->route('admin.devices.pending')
            ->with('success', "تم تفعيل الجهاز بنجاح. رقم التوكن: {$token}");
    }

    /**
     * عرض تفاصيل جهاز
     */
    public function show($id)
    {
        $device = Device::with(['subscription.user'])
            ->findOrFail($id);

        return view('admin.devices.show', compact('device'));
    }

    /**
     * تحديث معلومات جهاز
     */
    public function update(Request $request, $id)
    {
        $device = Device::findOrFail($id);

        $validated = $request->validate([
            'device_name' => 'required|string|max:255',
            'machine_name' => 'required|string|max:255',
            'status' => 'required|in:pending,connecting,active,suspended,disconnected',
            'ip_address' => 'nullable|ip',
            'additional_info' => 'nullable|string|max:1000'
        ]);

        $device->update($validated);

        return redirect()->route('admin.devices.show', $device->id)
            ->with('success', 'تم تحديث معلومات الجهاز بنجاح.');
    }

    /**
     * تعليق جهاز
     */
    public function suspend($id)
    {
        $device = Device::where('status', 'active')->findOrFail($id);

        $device->update(['status' => 'suspended']);

        return back()->with('success', 'تم تعليق الجهاز بنجاح.');
    }

    /**
     * إعادة تفعيل جهاز معلق
     */
    public function reactivate($id)
    {
        $device = Device::where('status', 'suspended')->findOrFail($id);

        $device->update(['status' => 'active']);

        return back()->with('success', 'تم إعادة تفعيل الجهاز بنجاح.');
    }

    /**
     * حذف جهاز
     */
    public function destroy($id)
    {
        $device = Device::findOrFail($id);

        $device->delete();

        return back()->with('success', 'تم حذف الجهاز بنجاح.');
    }

    /**
     * إعادة توليد توكن جهاز
     */
    public function regenerateToken($id)
    {
        $device = Device::findOrFail($id);

        $newToken = $device->generateToken();
        $device->save();

        return back()->with('success', "تم إعادة توليد التوكن بنجاح. التوكن الجديد: {$newToken}");
    }
}
