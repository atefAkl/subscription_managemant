<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Device extends Model
{
    protected $fillable = [
        'subscription_id',
        'device_identifier',
        'iphone_model',
        'device_nickname',
        'serial_number',
        'device_info',
        'last_token_update',
        'device_number',
        'device_version',
        'device_name',
        'machine_name',
        'token',
        'status',
        'activated_at',
        'last_connected_at',
        'ip_address'
    ];

    protected $casts = [
        'activated_at' => 'datetime',
        'last_connected_at' => 'datetime',
        'last_token_update' => 'datetime',
        'device_info' => 'array'
    ];

    // العلاقات
    public function subscription(): BelongsTo
    {
        return $this->belongsTo(Subscription::class);
    }

    // طرق مساعدة
    public function getStatusLabelAttribute(): string
    {
        $labels = [
            'pending' => 'في انتظار التفعيل',
            'connecting' => 'قيد التوصيل',
            'active' => 'نشط',
            'suspended' => 'موقوف',
            'disconnected' => 'منقطع'
        ];

        return $labels[$this->status] ?? $this->status;
    }

    public function generateActivationToken(): string
    {
        $token = strtoupper(Str::random(8));

        // تأكد من أن التوكن فريد
        while (self::where('activation_token', $token)->exists()) {
            $token = strtoupper(Str::random(8));
        }

        $this->activation_token = $token;
        return $token;
    }

    public function generateDeviceIdentifier(): string
    {
        // توليد رقم مميز من 10 خانات (أرقام وحروف)
        do {
            $identifier = strtoupper(Str::random(10));
        } while (self::where('device_identifier', $identifier)->exists());

        return $identifier;
    }

    public function getDisplayNameAttribute(): string
    {
        // إذا كان هناك اسم مميز، استخدمه، وإلا استخدم رقم الجهاز
        return $this->device_nickname ?: $this->device_number;
    }

    public function getFullModelNameAttribute(): string
    {
        return $this->iphone_model ?: $this->device_version;
    }

    public function activate(): void
    {
        $this->status = 'active';
        $this->activated_at = now();
        $this->save();
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    public function canBeActivated(): bool
    {
        return in_array($this->status, ['pending', 'connecting']);
    }

    public function getConnectionStatusAttribute(): string
    {
        if (!$this->last_connected_at) {
            return 'لم يتصل بعد';
        }

        $diffInMinutes = $this->last_connected_at->diffInMinutes(now());

        if ($diffInMinutes < 5) {
            return 'متصل الآن';
        } elseif ($diffInMinutes < 60) {
            return "منذ {$diffInMinutes} دقيقة";
        } elseif ($diffInMinutes < 1440) {
            $hours = floor($diffInMinutes / 60);
            return "منذ {$hours} ساعة";
        } else {
            $days = floor($diffInMinutes / 1440);
            return "منذ {$days} يوم";
        }
    }
}
