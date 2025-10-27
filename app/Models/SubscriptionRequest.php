<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SubscriptionRequest extends Model
{
    protected $fillable = [
        'user_id',
        'subscription_name',
        'device_count',
        'proposed_start_date',
        'notes',
        'status',
        'quoted_price',
        'payment_method',
        'admin_notes',
        'quoted_at',
        'payment_receipt',
        'paid_at'
    ];

    protected $casts = [
        'proposed_start_date' => 'date',
        'quoted_at' => 'datetime',
        'paid_at' => 'datetime'
    ];

    // العلاقات
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function subscription(): HasOne
    {
        return $this->hasOne(Subscription::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function requestDevices(): HasMany
    {
        return $this->hasMany(SubscriptionRequestDevice::class);
    }

    // طرق مساعدة
    public function getStatusLabelAttribute(): string
    {
        $labels = [
            'pending' => 'في انتظار عرض السعر',
            'quoted' => 'تم إرسال عرض السعر',
            'paid' => 'تم السداد',
            'active' => 'نشط',
            'approved' => 'تمت الموافقة',
            'rejected' => 'مرفوض'
        ];

        return $labels[$this->status] ?? $this->status;
    }

    public function canBeQuoted(): bool
    {
        return $this->status === 'pending';
    }

    public function canBePaid(): bool
    {
        return $this->status === 'quoted';
    }

    public function canBeActivated(): bool
    {
        return $this->status === 'paid';
    }
}
