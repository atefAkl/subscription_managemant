<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Device;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DeviceController extends Controller
{
    /**
     * عرض جميع أجهزة العميل من كل الاشتراكات
     */
    public function index()
    {
        $user = Auth::user();

        // جلب جميع الأجهزة من جميع اشتراكات العميل
        $subscriptions = Subscription::where('user_id', $user->id)
            ->with(['devices' => function ($query) {
                $query->orderBy('created_at', 'desc');
            }])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('client.devices.index', compact('subscriptions'));
    }

    /**
     * تحديث اسم الجهاز
     */
    public function updateName(Request $request, Device $device)
    {
        // التحقق من أن الجهاز يخص العميل الحالي
        if ($device->subscription->user_id !== Auth::id()) {
            return response()->json(['success' => false, 'message' => 'غير مصرح لك بتعديل هذا الجهاز'], 403);
        }

        $request->validate([
            'device_nickname' => 'required|string|max:100'
        ]);

        $device->update([
            'device_nickname' => $request->device_nickname
        ]);

        return response()->json([
            'success' => true,
            'message' => 'تم تحديث اسم الجهاز بنجاح',
            'new_name' => $device->device_nickname
        ]);
    }

    /**
     * حذف جهاز (فقط إذا لم يتم تفعيله)
     */
    public function destroy(Device $device)
    {
        // التحقق من أن الجهاز يخص العميل الحالي
        if ($device->subscription->user_id !== Auth::id()) {
            return response()->json(['success' => false, 'message' => 'غير مصرح لك بحذف هذا الجهاز'], 403);
        }

        // التحقق من أن الجهاز غير مفعل
        if ($device->status !== 'pending') {
            return response()->json(['success' => false, 'message' => 'لا يمكن حذف جهاز مفعل'], 400);
        }

        $device->delete();

        return response()->json([
            'success' => true,
            'message' => 'تم حذف الجهاز بنجاح'
        ]);
    }
}
