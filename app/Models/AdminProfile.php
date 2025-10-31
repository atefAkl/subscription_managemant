<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class AdminProfile extends Profile
{
  use HasFactory;

  protected $table = 'admin_profiles';

  protected $fillable = [
    'user_id',
    'department',
    'position',
    'permissions_level',
    'access_level',
    'notes',
    'preferences',
    'settings'
  ];

  protected $casts = [
    'permissions_level' => 'integer',
    'access_level' => 'integer',
    'preferences' => 'array',
    'settings' => 'array',
  ];

  /**
   * صلاحيات المدير
   */
  const PERMISSIONS = [
    'manage_users' => 'إدارة المستخدمين',
    'manage_clients' => 'إدارة العملاء',
    'manage_subscriptions' => 'إدارة الاشتراكات',
    'view_statistics' => 'عرض الإحصائيات',
    'system_settings' => 'إعدادات النظام',
    'manage_devices' => 'إدارة الأجهزة',
    'financial_reports' => 'التقارير المالية',
    'user_analytics' => 'تحليلات المستخدمين'
  ];

  /**
   * مستويات الصلاحيات
   */
  const ACCESS_LEVELS = [
    1 => 'محدود', // Limited
    2 => 'متوسط', // Medium
    3 => 'كامل',  // Full
    4 => 'سوبر أدمين' // Super Admin
  ];

  /**
   * الحصول على اسم العرض
   */
  public function getDisplayName(): string
  {
    return $this->user->name . ' - ' . ($this->position ?? 'مدير');
  }

  /**
   * الحصول على الصلاحيات حسب مستوى الوصول
   */
  public function getPermissions(): array
  {
    $allPermissions = array_keys(self::PERMISSIONS);

    switch ($this->access_level) {
      case 1: // محدود
        return ['view_statistics', 'manage_devices'];

      case 2: // متوسط
        return ['view_statistics', 'manage_devices', 'manage_clients', 'user_analytics'];

      case 3: // كامل
        return array_diff($allPermissions, ['system_settings']);

      case 4: // سوبر أدمين
        return $allPermissions;

      default:
        return ['view_statistics'];
    }
  }

  /**
   * التحقق من كونه سوبر أدمين
   */
  public function isSuperAdmin(): bool
  {
    return $this->access_level === 4 || $this->user->is_app_admin;
  }

  /**
   * الحصول على مستوى الوصول كنص
   */
  public function getAccessLevelText(): string
  {
    return self::ACCESS_LEVELS[$this->access_level] ?? 'غير محدد';
  }

  /**
   * الحصول على رقم الموظف
   */
  public function getEmployeeNumber(): ?string
  {
    return $this->user->employee_number;
  }

  /**
   * scope للمديرين النشطين
   */
  public function scopeActive($query)
  {
    return $query->whereHas('user', function ($q) {
      $q->where('status', 'active');
    });
  }

  /**
   * scope حسب مستوى الوصول
   */
  public function scopeByAccessLevel($query, int $level)
  {
    return $query->where('access_level', $level);
  }
}
