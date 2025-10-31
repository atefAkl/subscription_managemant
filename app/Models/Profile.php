<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

abstract class Profile extends Model
{
  use HasFactory;

  protected $guarded = [];

  /**
   * العلاقة مع المستخدم
   */
  public function user()
  {
    return $this->belongsTo(User::class);
  }

  /**
   * الصفات المشتركة بين جميع البروفايلات
   */
  protected $casts = [
    'preferences' => 'array',
    'settings' => 'array',
    'created_at' => 'datetime',
    'updated_at' => 'datetime',
  ];

  /**
   * التحقق من صحة البيانات الأساسية
   */
  protected static function boot()
  {
    parent::boot();

    static::creating(function ($profile) {
      if (!$profile->user_id) {
        throw new \Exception('Profile must be associated with a user');
      }
    });
  }

  /**
   * الحصول على معلومات البروفايل
   */
  abstract public function getDisplayName(): string;

  /**
   * الحصول على صلاحيات البروفايل
   */
  abstract public function getPermissions(): array;

  /**
   * التحقق من صلاحية معينة
   */
  public function hasPermission(string $permission): bool
  {
    return in_array($permission, $this->getPermissions());
  }
}
