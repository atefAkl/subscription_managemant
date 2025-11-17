<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SubscriptionComment;
use App\Models\SubscriptionRequest;
use App\Models\Subscription;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SubscriptionCommentController extends Controller
{
    /**
     * عرض التعليقات لطلب اشتراك أو اشتراك
     */
    public function index(Request $request)
    {
        $subscriptionRequestId = $request->get('subscription_request_id');
        $subscriptionId = $request->get('subscription_id');

        $query = SubscriptionComment::with('user');

        if ($subscriptionRequestId) {
            $query->where('subscription_request_id', $subscriptionRequestId);
        }

        if ($subscriptionId) {
            $query->where('subscription_id', $subscriptionId);
        }

        $comments = $query->orderBy('created_at', 'asc')->get();

        return response()->json([
            'success' => true,
            'comments' => $comments->map(function ($comment) {
                return [
                    'id' => $comment->id,
                    'message' => $comment->message,
                    'sender_name' => $comment->sender_name,
                    'is_admin' => $comment->is_admin,
                    'comment_type' => $comment->comment_type,
                    'comment_type_text' => $comment->comment_type_text,
                    'attachments' => $comment->attachments,
                    'created_at' => $comment->created_at->format('Y-m-d H:i:s'),
                    'created_at_human' => $comment->created_at->diffForHumans()
                ];
            })
        ]);
    }

    /**
     * إضافة تعليق جديد
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'message' => 'required|string|max:1000',
                'subscription_request_id' => 'nullable|exists:subscription_requests,id',
                'subscription_id' => 'nullable|exists:subscriptions,id',
                'comment_type' => 'in:message,status_change,payment_verification',
                'attachments.*' => 'file|mimes:jpg,jpeg,png,pdf|max:5120'
            ]);

            // التأكد من وجود إما subscription_request_id أو subscription_id
            if (!$request->subscription_request_id && !$request->subscription_id) {
                return response()->json([
                    'success' => false,
                    'message' => 'يجب تحديد طلب الاشتراك أو الاشتراك'
                ], 400);
            }

            $attachments = [];

            // رفع المرفقات إن وجدت
            if ($request->hasFile('attachments')) {
                foreach ($request->file('attachments') as $file) {
                    $path = $file->store('subscription-comments', 'public');
                    $attachments[] = [
                        'name' => $file->getClientOriginalName(),
                        'path' => $path,
                        'size' => $file->getSize(),
                        'type' => $file->getMimeType()
                    ];
                }
            }

            $comment = SubscriptionComment::create([
                'subscription_request_id' => $request->subscription_request_id,
                'subscription_id' => $request->subscription_id,
                'user_id' => Auth::id(),
                'message' => $request->message,
                'comment_type' => $request->comment_type ?? 'message',
                'attachments' => $attachments,
                'is_admin' => (Auth::user()->role ?? 'client') === 'admin'
            ]);

            $comment->load('user');

            return response()->json([
                'success' => true,
                'message' => 'تم إضافة التعليق بنجاح',
                'comment' => [
                    'id' => $comment->id,
                    'message' => $comment->message,
                    'sender_name' => $comment->sender_name,
                    'is_admin' => $comment->is_admin,
                    'comment_type' => $comment->comment_type,
                    'comment_type_text' => $comment->comment_type_text,
                    'attachments' => $comment->attachments,
                    'created_at' => $comment->created_at->format('Y-m-d H:i:s'),
                    'created_at_human' => $comment->created_at->diffForHumans()
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء إضافة التعليق: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * حذف تعليق (للمديرين فقط)
     */
    public function destroy($id)
    {
        if (Auth::user()->role !== 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'غير مصرح لك بحذف التعليقات'
            ], 403);
        }

        $comment = SubscriptionComment::findOrFail($id);

        // حذف المرفقات من التخزين
        if ($comment->attachments) {
            foreach ($comment->attachments as $attachment) {
                Storage::disk('public')->delete($attachment['path']);
            }
        }

        $comment->delete();

        return response()->json([
            'success' => true,
            'message' => 'تم حذف التعليق بنجاح'
        ]);
    }
}
