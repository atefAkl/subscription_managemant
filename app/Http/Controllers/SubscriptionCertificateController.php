<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SubscriptionCertificate;
use App\Models\Subscription;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SubscriptionCertificateController extends Controller
{
    /**
     * عرض شهادات اشتراك معين
     */
    public function index($subscriptionId)
    {
        $subscription = Subscription::with(['certificates.verifiedBy', 'user'])->findOrFail($subscriptionId);

        // التحقق من الصلاحية
        if (Auth::user()->role !== 'admin' && $subscription->user_id !== Auth::id()) {
            abort(403, 'غير مصرح لك بعرض هذه الشهادات');
        }

        return response()->json([
            'success' => true,
            'subscription' => [
                'id' => $subscription->id,
                'name' => $subscription->name,
                'status' => $subscription->status,
                'user_name' => $subscription->user->name
            ],
            'certificates' => $subscription->certificates->map(function ($cert) {
                return [
                    'id' => $cert->id,
                    'certificate_key' => $cert->certificate_key,
                    'status' => $cert->status,
                    'status_text' => $cert->status_text,
                    'status_color' => $cert->status_color,
                    'verified_by' => $cert->verifiedBy ? $cert->verifiedBy->name : null,
                    'verified_at' => $cert->verified_at ? $cert->verified_at->format('Y-m-d H:i') : null,
                    'notes' => $cert->notes,
                    'created_at' => $cert->created_at->format('Y-m-d H:i')
                ];
            })
        ]);
    }

    /**
     * إضافة شهادة جديدة من العميل
     */
    public function store(Request $request, $subscriptionId)
    {
        $request->validate([
            'certificate_key' => [
                'required',
                'string',
                'size:10',
                'regex:/^[A-Z0-9]{10}$/',
                'unique:subscription_certificates,certificate_key'
            ]
        ], [
            'certificate_key.required' => 'رقم الشهادة مطلوب',
            'certificate_key.size' => 'رقم الشهادة يجب أن يكون 10 خانات بالضبط',
            'certificate_key.regex' => 'رقم الشهادة يجب أن يحتوي على أرقام وحروف كبيرة فقط',
            'certificate_key.unique' => 'رقم الشهادة مستخدم من قبل'
        ]);

        $subscription = Subscription::findOrFail($subscriptionId);

        // التحقق من أن العميل يملك هذا الاشتراك
        if ($subscription->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'غير مصرح لك بإضافة شهادات لهذا الاشتراك'
            ], 403);
        }

        // التحقق من أن الاشتراك في حالة تسمح بإضافة الشهادات
        if (!in_array($subscription->status, ['pending', 'active'])) {
            return response()->json([
                'success' => false,
                'message' => 'لا يمكن إضافة شهادات لهذا الاشتراك في الوقت الحالي'
            ], 400);
        }

        $certificate = SubscriptionCertificate::create([
            'subscription_id' => $subscriptionId,
            'certificate_key' => strtoupper($request->certificate_key),
            'status' => 'pending'
        ]);

        return response()->json([
            'success' => true,
            'message' => 'تم إضافة الشهادة بنجاح، في انتظار المراجعة',
            'certificate' => [
                'id' => $certificate->id,
                'certificate_key' => $certificate->certificate_key,
                'status' => $certificate->status,
                'status_text' => $certificate->status_text,
                'status_color' => $certificate->status_color,
                'created_at' => $certificate->created_at->format('Y-m-d H:i')
            ]
        ]);
    }

    /**
     * إضافة شهادة من الإدارة
     */
    public function adminStore(Request $request, $subscriptionId)
    {
        if (Auth::user()->role !== 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'غير مصرح لك بهذا الإجراء'
            ], 403);
        }

        $request->validate([
            'certificate_key' => [
                'required',
                'string',
                'size:10',
                'regex:/^[A-Z0-9]{10}$/',
                'unique:subscription_certificates,certificate_key'
            ],
            'status' => 'in:pending,verified,active,rejected',
            'notes' => 'nullable|string|max:500'
        ]);

        $subscription = Subscription::findOrFail($subscriptionId);

        $certificate = SubscriptionCertificate::create([
            'subscription_id' => $subscriptionId,
            'certificate_key' => strtoupper($request->certificate_key),
            'status' => $request->status ?? 'verified',
            'verified_by' => Auth::id(),
            'verified_at' => now(),
            'notes' => $request->notes
        ]);

        return response()->json([
            'success' => true,
            'message' => 'تم إضافة الشهادة بنجاح',
            'certificate' => [
                'id' => $certificate->id,
                'certificate_key' => $certificate->certificate_key,
                'status' => $certificate->status,
                'status_text' => $certificate->status_text,
                'status_color' => $certificate->status_color,
                'notes' => $certificate->notes,
                'created_at' => $certificate->created_at->format('Y-m-d H:i')
            ]
        ]);
    }

    /**
     * تحديث حالة الشهادة (للإدارة فقط)
     */
    public function updateStatus(Request $request, $certificateId)
    {
        if (Auth::user()->role !== 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'غير مصرح لك بهذا الإجراء'
            ], 403);
        }

        $request->validate([
            'status' => 'required|in:pending,verified,active,rejected',
            'notes' => 'nullable|string|max:500'
        ]);

        $certificate = SubscriptionCertificate::findOrFail($certificateId);

        $certificate->update([
            'status' => $request->status,
            'verified_by' => Auth::id(),
            'verified_at' => now(),
            'notes' => $request->notes
        ]);

        return response()->json([
            'success' => true,
            'message' => 'تم تحديث حالة الشهادة بنجاح',
            'certificate' => [
                'id' => $certificate->id,
                'certificate_key' => $certificate->certificate_key,
                'status' => $certificate->status,
                'status_text' => $certificate->status_text,
                'status_color' => $certificate->status_color,
                'notes' => $certificate->notes,
                'verified_at' => $certificate->verified_at->format('Y-m-d H:i')
            ]
        ]);
    }

    /**
     * تفعيل الاشتراك بعد التحقق من الشهادات
     */
    public function activateSubscription($subscriptionId)
    {
        if (Auth::user()->role !== 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'غير مصرح لك بهذا الإجراء'
            ], 403);
        }

        $subscription = Subscription::with('certificates')->findOrFail($subscriptionId);

        // التحقق من وجود شهادات مفعلة
        $activeCertificates = $subscription->certificates()->where('status', 'active')->count();

        if ($activeCertificates === 0) {
            return response()->json([
                'success' => false,
                'message' => 'يجب تفعيل شهادة واحدة على الأقل قبل تفعيل الاشتراك'
            ], 400);
        }

        DB::transaction(function () use ($subscription) {
            $subscription->update([
                'status' => 'active',
                'activated_at' => now()
            ]);

            // إضافة تعليق حول تفعيل الاشتراك
            $subscription->comments()->create([
                'user_id' => Auth::id(),
                'message' => 'تم تفعيل الاشتراك بنجاح',
                'comment_type' => 'status_change',
                'is_admin' => true
            ]);
        });

        return response()->json([
            'success' => true,
            'message' => 'تم تفعيل الاشتراك بنجاح'
        ]);
    }

    /**
     * حذف شهادة
     */
    public function destroy($certificateId)
    {
        $certificate = SubscriptionCertificate::findOrFail($certificateId);

        // التحقق من الصلاحية
        if (Auth::user()->role !== 'admin' && $certificate->subscription->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'غير مصرح لك بحذف هذه الشهادة'
            ], 403);
        }

        $certificate->delete();

        return response()->json([
            'success' => true,
            'message' => 'تم حذف الشهادة بنجاح'
        ]);
    }
}
