<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class SubscriptionRequestDevice extends Model
{
    protected $fillable = [
        'subscription_request_id',
        'device_identifier',
        'iphone_model',
        'device_nickname',
        'special_requirements'
    ];

    // العلاقات
    public function subscriptionRequest(): BelongsTo
    {
        return $this->belongsTo(SubscriptionRequest::class);
    }

    // طرق مساعدة
    public function getFullModelNameAttribute(): string
    {
        $models = [
            'iPhone 15 Pro Max' => 'iPhone 15 Pro Max',
            'iPhone 15 Pro' => 'iPhone 15 Pro',
            'iPhone 15 Plus' => 'iPhone 15 Plus',
            'iPhone 15' => 'iPhone 15',
            'iPhone 14 Pro Max' => 'iPhone 14 Pro Max',
            'iPhone 14 Pro' => 'iPhone 14 Pro',
            'iPhone 14 Plus' => 'iPhone 14 Plus',
            'iPhone 14' => 'iPhone 14',
            'iPhone 13 Pro Max' => 'iPhone 13 Pro Max',
            'iPhone 13 Pro' => 'iPhone 13 Pro',
            'iPhone 13 mini' => 'iPhone 13 mini',
            'iPhone 13' => 'iPhone 13',
            'iPhone 12 Pro Max' => 'iPhone 12 Pro Max',
            'iPhone 12 Pro' => 'iPhone 12 Pro',
            'iPhone 12 mini' => 'iPhone 12 mini',
            'iPhone 12' => 'iPhone 12',
            'iPhone 11 Pro Max' => 'iPhone 11 Pro Max',
            'iPhone 11 Pro' => 'iPhone 11 Pro',
            'iPhone 11' => 'iPhone 11',
        ];

        return $models[$this->iphone_model] ?? $this->iphone_model;
    }

    public function getDisplayNameAttribute(): string
    {
        return $this->device_nickname ?: $this->full_model_name;
    }

    /**
     * إنشاء رقم مميز فريد
     */
    public static function generateDeviceIdentifier(): string
    {
        do {
            $identifier = 'IPH' . strtoupper(Str::random(7));
        } while (self::where('device_identifier', $identifier)->exists());

        return $identifier;
    }
}
