<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SubscriptionComment extends Model
{
    protected $fillable = [
        'subscription_request_id',
        'subscription_id',
        'user_id',
        'comment_type',
        'message',
        'attachments',
        'is_admin'
    ];

    protected $casts = [
        'attachments' => 'array',
        'is_admin' => 'boolean',
    ];

    /**
     * العلاقة مع المستخدم
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * العلاقة مع طلب الاشتراك
     */
    public function subscriptionRequest(): BelongsTo
    {
        return $this->belongsTo(SubscriptionRequest::class);
    }

    /**
     * العلاقة مع الاشتراك
     */
    public function subscription(): BelongsTo
    {
        return $this->belongsTo(Subscription::class);
    }

    /**
     * تحديد نوع التعليق
     */
    public function getCommentTypeTextAttribute(): string
    {
        return match ($this->comment_type) {
            'message' => 'رسالة',
            'status_change' => 'تغيير حالة',
            'payment_verification' => 'تحقق من الدفع',
            default => 'غير محدد'
        };
    }

    /**
     * تحديد اسم المرسل للعرض
     */
    public function getSenderNameAttribute(): string
    {
        if ($this->is_admin) {
            return "مسؤول رقم {$this->user->serial_number}";
        }
        return $this->user->name;
    }
}
