<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class SubscriptionRequest extends Model
{
    protected $fillable = [
        'user_id',
        'serial_number',
        'subscription_name',
        'device_count',
        'proposed_start_date',
        'notes',
        'status',
        'is_demo',
        'quoted_price',
        'payment_method',
        'admin_notes',
        'quoted_at',
        'payment_receipt',
        'paid_at',
        'activated_at',
        'expires_at',
        'suspended_at',
        'renewed_at',
        'suspension_reason',
        'payment_verification_status',
        'payment_verified_by',
        'payment_verified_at',
        'payment_verification_notes'
    ];

    protected $casts = [
        'proposed_start_date' => 'date',
        'quoted_at' => 'datetime',
        'paid_at' => 'datetime',
        'activated_at' => 'datetime',
        'expires_at' => 'datetime',
        'suspended_at' => 'datetime',
        'renewed_at' => 'datetime',
        'payment_verified_at' => 'datetime'
    ];

    // العلاقات
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public static function generateSerialNumber()
    {
        //generate unique serial number
        $serialNumber = Str::random(16);
        while (self::where('serial_number', $serialNumber)->exists()) {
            $serialNumber = Str::random(16);
        }
        return $serialNumber;
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

    public function devices(): HasMany
    {
        return $this->hasMany(ClientDevice::class, 'subscription_id', 'id');
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'user_id', 'id');
    }

    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(SubscriptionComment::class);
    }

    public function paymentVerifiedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'payment_verified_by');
    }

    /**
     * الحصول على اسم وسيلة الدفع بالعربية
     */
    public function getPaymentMethodName(): string
    {
        $methods = [
            'vodafone_cash' => 'فودافون كاش',
            'etisalat_cash' => 'اتصالات كاش',
            'orange_cash' => 'أورانج كاش',
            'fawry' => 'فوري',
            'bank_transfer' => 'تحويل بنكي',
            'visa_card' => 'فيزا كارد'
        ];

        return $methods[$this->payment_method] ?? $this->payment_method ?? 'غير محدد';
    }

    // طرق مساعدة
    public function getStatusLabelAttribute(): string
    {
        // تمييز حالة pending بين طلبات الديمو وغير الديمو
        if ($this->status === 'pending') {
            return $this->is_demo ? 'في انتظار التحقق من الدفع' : 'قيد المراجعة';
        }

        $labels = [
            'quoted' => 'تم إرسال عرض السعر',
            'paid' => 'تم قبول الدفع',
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
