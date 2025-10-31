<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;

class ClientProfile extends Profile
{
  use HasFactory;

  protected $table = 'client_profiles';

  protected $fillable = [
    'user_id',
    'subscription_type',
    'subscription_status',
    'subscription_start_date',
    'subscription_end_date',
    'device_limit',
    'devices_count',
    'payment_status',
    'billing_cycle',
    'client_notes',
    'preferences',
    'settings'
  ];

  protected $casts = [
    'subscription_start_date' => 'date',
    'subscription_end_date' => 'date',
    'device_limit' => 'integer',
    'devices_count' => 'integer',
    'preferences' => 'array',
    'settings' => 'array',
  ];

  /**
   * أنواع الاشتراكات
   */
  const SUBSCRIPTION_TYPES = [
    'basic' => 'أساسي',
    'premium' => 'مميز',
    'enterprise' => 'مؤسسي'
  ];

  /**
   * حالات الاشتراك
   */
  const SUBSCRIPTION_STATUS = [
    'active' => 'نشط',
    'inactive' => 'غير نشط',
    'suspended' => 'معلق',
    'expired' => 'منتهي الصلاحية',
    'trial' => 'تجريبي'
  ];

  /**
   * حالات الدفع
   */
  const PAYMENT_STATUS = [
    'paid' => 'مدفوع',
    'pending' => 'معلق',
    'overdue' => 'متأخر',
    'failed' => 'فشل في الدفع'
  ];

  /**
   * دورات الفوترة
   */
  const BILLING_CYCLES = [
    'monthly' => 'شهري',
    'quarterly' => 'ربع سنوي',
    'yearly' => 'سنوي'
  ];

  /**
   * الحصول على اسم العرض
   */
  public function getDisplayName(): string
  {
    return $this->user->name . ' - عميل ' . $this->getSubscriptionTypeText();
  }

  /**
   * صلاحيات العميل الأساسية
   */
  public function getPermissions(): array
  {
    $basePermissions = [
      'view_profile',
      'manage_devices',
      'view_subscription'
    ];

    // صلاحيات إضافية حسب نوع الاشتراك
    if ($this->subscription_type === 'premium') {
      $basePermissions[] = 'advanced_analytics';
      $basePermissions[] = 'priority_support';
    }

    if ($this->subscription_type === 'enterprise') {
      $basePermissions[] = 'advanced_analytics';
      $basePermissions[] = 'priority_support';
      $basePermissions[] = 'custom_reports';
      $basePermissions[] = 'api_access';
    }

    return $basePermissions;
  }

  /**
   * التحقق من نشاط الاشتراك
   */
  public function isSubscriptionActive(): bool
  {
    return $this->subscription_status === 'active' &&
      $this->subscription_end_date &&
      $this->subscription_end_date->isFuture();
  }

  /**
   * التحقق من انتهاء الاشتراك قريباً
   */
  public function isSubscriptionExpiringSoon(int $days = 7): bool
  {
    if (!$this->subscription_end_date) {
      return false;
    }

    return $this->subscription_end_date->diffInDays(now()) <= $days &&
      $this->subscription_end_date->isFuture();
  }

  /**
   * الحصول على نوع الاشتراك كنص
   */
  public function getSubscriptionTypeText(): string
  {
    return self::SUBSCRIPTION_TYPES[$this->subscription_type] ?? 'غير محدد';
  }

  /**
   * الحصول على حالة الاشتراك كنص
   */
  public function getSubscriptionStatusText(): string
  {
    return self::SUBSCRIPTION_STATUS[$this->subscription_status] ?? 'غير محدد';
  }

  /**
   * الحصول على حالة الدفع كنص
   */
  public function getPaymentStatusText(): string
  {
    return self::PAYMENT_STATUS[$this->payment_status] ?? 'غير محدد';
  }

  /**
   * الحصول على دورة الفوترة كنص
   */
  public function getBillingCycleText(): string
  {
    return self::BILLING_CYCLES[$this->billing_cycle] ?? 'غير محدد';
  }

  /**
   * الحصول على الأجهزة المتاحة المتبقية
   */
  public function getAvailableDevices(): int
  {
    return max(0, $this->device_limit - $this->devices_count);
  }

  /**
   * التحقق من إمكانية إضافة جهاز جديد
   */
  public function canAddDevice(): bool
  {
    return $this->getAvailableDevices() > 0 && $this->isSubscriptionActive();
  }

  /**
   * الحصول على أيام الاشتراك المتبقية
   */
  public function getRemainingDays(): ?int
  {
    if (!$this->subscription_end_date) {
      return null;
    }

    return $this->subscription_end_date->diffInDays(now(), false);
  }

  /**
   * scope للعملاء النشطين
   */
  public function scopeActive($query)
  {
    return $query->where('subscription_status', 'active')
      ->where('subscription_end_date', '>', now());
  }

  /**
   * scope للعملاء الجدد (آخر 30 يوم)
   */
  public function scopeNew($query, int $days = 30)
  {
    return $query->where('subscription_start_date', '>=', now()->subDays($days));
  }

  /**
   * scope للاشتراكات المنتهية الصلاحية قريباً
   */
  public function scopeExpiringSoon($query, int $days = 7)
  {
    return $query->where('subscription_end_date', '<=', now()->addDays($days))
      ->where('subscription_end_date', '>', now());
  }

  /**
   * scope حسب نوع الاشتراك
   */
  public function scopeBySubscriptionType($query, string $type)
  {
    return $query->where('subscription_type', $type);
  }

  /**
   * scope حسب حالة الدفع
   */
  public function scopeByPaymentStatus($query, string $status)
  {
    return $query->where('payment_status', $status);
  }
}
