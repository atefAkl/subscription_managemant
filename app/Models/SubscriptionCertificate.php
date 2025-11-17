<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SubscriptionCertificate extends Model
{
    protected $fillable = [
        'subscription_id',
        'certificate_key',
        'status',
        'verified_by',
        'verified_at',
        'notes'
    ];

    protected $casts = [
        'verified_at' => 'datetime',
    ];

    /**
     * العلاقة مع الاشتراك
     */
    public function subscription(): BelongsTo
    {
        return $this->belongsTo(Subscription::class);
    }

    /**
     * العلاقة مع المستخدم الذي تحقق من الشهادة
     */
    public function verifiedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    /**
     * تحديد حالة الشهادة
     */
    public function getStatusTextAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'في الانتظار',
            'verified' => 'تم التحقق',
            'active' => 'مفعل',
            'rejected' => 'مرفوض',
            default => 'غير محدد'
        };
    }

    /**
     * تحديد لون الحالة للعرض
     */
    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'warning',
            'verified' => 'info',
            'active' => 'success',
            'rejected' => 'danger',
            default => 'secondary'
        };
    }

    /**
     * التحقق من صحة مفتاح الشهادة
     */
    public static function validateCertificateKey(string $key): bool
    {
        return preg_match('/^[A-Z0-9]{10}$/', $key);
    }
}
