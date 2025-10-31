<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class ClientDevice extends Model
{
  use HasFactory;

  protected $fillable = [
    'user_id',
    'subscription_id',
    'device_name',
    'device_serial',
    'device_type',
    'device_model',
    'ios_version',
    'status',
    'activation_date',
    'last_connection',
    'device_info',
    'notes'
  ];

  protected $casts = [
    'activation_date' => 'datetime',
    'last_connection' => 'datetime',
    'device_info' => 'array',
  ];

  /**
   * Apple device types
   */
  const DEVICE_TYPES = [
    'iphone' => 'آيفون',
    'ipad' => 'آيباد',
    'mac' => 'ماك',
    'apple_tv' => 'أبل تي في',
    'apple_watch' => 'أبل ووتش'
  ];

  /**
   * Device statuses (تفعيل/إيقاف فقط)
   */
  const DEVICE_STATUS = [
    'active' => 'مُفعّل',
    'inactive' => 'موقوف'
  ];

  /**
   * Relationship with user
   */
  public function user()
  {
    return $this->belongsTo(User::class);
  }

  /**
   * Relationship with subscription
   */
  public function subscription()
  {
    return $this->belongsTo(ClientProfile::class, 'subscription_id');
  }

  /**
   * Get device type text
   */
  public function getDeviceTypeText(): string
  {
    return self::DEVICE_TYPES[$this->device_type] ?? 'غير محدد';
  }

  /**
   * Get device status text
   */
  public function getDeviceStatusText(): string
  {
    return self::DEVICE_STATUS[$this->status] ?? 'غير محدد';
  }

  /**
   * Get Apple device type icon
   */
  public function getDeviceTypeIcon(): string
  {
    $icons = [
      'iphone' => 'fab fa-apple',
      'ipad' => 'fas fa-tablet-alt',
      'mac' => 'fas fa-laptop',
      'apple_tv' => 'fas fa-tv',
      'apple_watch' => 'fas fa-clock'
    ];

    return $icons[$this->device_type] ?? 'fab fa-apple';
  }

  /**
   * Get status color classes
   */
  public function getStatusColor(): string
  {
    $colors = [
      'active' => 'bg-green-100 text-green-800',
      'inactive' => 'bg-red-100 text-red-800'
    ];

    return $colors[$this->status] ?? 'bg-gray-100 text-gray-800';
  }

  /**
   * Get device serial number formatted
   */
  public function getFormattedSerial(): string
  {
    return $this->device_serial ? strtoupper($this->device_serial) : 'غير محدد';
  }

  /**
   * Get device full info for display
   */
  public function getDeviceFullInfo(): string
  {
    $info = $this->getDeviceTypeText();
    if ($this->device_model) {
      $info .= ' - ' . $this->device_model;
    }
    if ($this->ios_version) {
      $info .= ' (iOS ' . $this->ios_version . ')';
    }
    return $info;
  }

  /**
   * Alias for getDeviceStatusText() (for compatibility)
   */
  public function getStatusText(): string
  {
    return $this->getDeviceStatusText();
  }

  /**
   * Check if device is online (connected in last 5 minutes)
   */
  public function isOnline(): bool
  {
    return $this->last_connection &&
      $this->status === 'active' &&
      $this->last_connection->diffInMinutes(now()) <= 5;
  }

  /**
   * Get connection status
   */
  public function getConnectionStatus(): string
  {
    if ($this->status !== 'active') {
      return $this->getDeviceStatusText();
    }

    if (!$this->last_connection) {
      return 'لم يتصل بعد';
    }

    $minutesAgo = $this->last_connection->diffInMinutes(now());

    if ($minutesAgo <= 5) {
      return 'متصل الآن';
    } elseif ($minutesAgo <= 60) {
      return "آخر اتصال منذ {$minutesAgo} دقيقة";
    } elseif ($minutesAgo <= 1440) { // 24 hours
      $hoursAgo = round($minutesAgo / 60);
      return "آخر اتصال منذ {$hoursAgo} ساعة";
    } else {
      return 'آخر اتصال: ' . $this->last_connection->format('Y-m-d');
    }
  }

  /**
   * Generate unique device token
   */
  public static function generateDeviceToken(): string
  {
    do {
      $token = 'DT' . strtoupper(bin2hex(random_bytes(16)));
    } while (self::where('device_token', $token)->exists());

    return $token;
  }

  /**
   * Update last connection time
   */
  public function updateConnection(?string $ipAddress = null): void
  {
    $this->update([
      'last_connection' => now(),
      'ip_address' => $ipAddress ?? $this->ip_address
    ]);
  }

  /**
   * Scope for active devices
   */
  public function scopeActive($query)
  {
    return $query->where('status', 'active');
  }

  /**
   * Scope for online devices (connected in last 5 minutes)
   */
  public function scopeOnline($query)
  {
    return $query->where('status', 'active')
      ->where('last_connection', '>=', now()->subMinutes(5));
  }

  /**
   * Scope by device type
   */
  public function scopeByType($query, string $type)
  {
    return $query->where('device_type', $type);
  }
}
