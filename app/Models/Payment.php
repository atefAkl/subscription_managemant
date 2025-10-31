<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    protected $fillable = [
        'subscription_request_id',
        'user_id',
        'amount',
        'payment_method',
        'transaction_reference',
        'receipt_path',
        'status',
        'admin_notes',
        'paid_at',
        'verified_at',
        'verified_by'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'paid_at' => 'datetime',
        'verified_at' => 'datetime',
    ];

    // العلاقات
    public function subscriptionRequest(): BelongsTo
    {
        return $this->belongsTo(SubscriptionRequest::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function verifiedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    // طرق مساعدة
    public function getStatusLabelAttribute(): string
    {
        $labels = [
            'pending_verification' => 'في انتظار التحقق',
            'verified' => 'تم التحقق',
            'rejected' => 'مرفوض'
        ];

        return $labels[$this->status] ?? $this->status;
    }

    public function canBeVerified(): bool
    {
        return $this->status === 'pending_verification';
    }

    public function verify($adminId, $notes = null): void
    {
        $this->status = 'verified';
        $this->verified_at = now();
        $this->verified_by = $adminId;
        $this->admin_notes = $notes ?? 'Thanks for your payment.';
        $this->save();
    }

    public function reject($adminId, $notes = null): void
    {
        $this->status = 'rejected';
        $this->verified_by = $adminId;
        $this->admin_notes = $notes;
        $this->save();
    }
}
