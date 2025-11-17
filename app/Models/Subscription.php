<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

class Subscription extends Model
{
    protected $fillable = [
        'user_id',
        'subscription_request_id',
        'name',
        'device_count',
        'price',
        'paid_amount',
        'remaining_amount',
        'start_date',
        'end_date',
        'status',
        'payment_confirmed_at',
        'description',
        'features'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'payment_confirmed_at' => 'datetime',
        'features' => 'array',
        'price' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'remaining_amount' => 'decimal:2'
    ];

    // العلاقات
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function subscriptionRequest(): BelongsTo
    {
        return $this->belongsTo(SubscriptionRequest::class);
    }

    public function devices(): HasMany
    {
        return $this->hasMany(Device::class);
    }

    public function certificates(): HasMany
    {
        return $this->hasMany(SubscriptionCertificate::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(SubscriptionComment::class);
    }

    // طرق مساعدة
    public function getStatusLabelAttribute(): string
    {
        $labels = [
            'active' => 'نشط',
            'suspended' => 'موقوف',
            'expired' => 'منتهي',
            'cancelled' => 'ملغي'
        ];

        return $labels[$this->status] ?? $this->status;
    }

    public function getDaysRemainingAttribute(): int
    {
        if ($this->status !== 'active') {
            return 0;
        }

        return max(0, Carbon::now()->diffInDays($this->end_date, false));
    }

    public function isActive(): bool
    {
        return $this->status === 'active' && $this->end_date->isFuture();
    }

    public function isExpired(): bool
    {
        return $this->end_date->isPast();
    }

    public function getActiveDevicesCount(): int
    {
        return $this->devices()->where('status', 'active')->count();
    }

    public function getRemainingDevicesCount(): int
    {
        return $this->device_count - $this->devices()->count();
    }
}
