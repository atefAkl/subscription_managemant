<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Client extends Model
{
  protected $table = 'users';

  protected $fillable = [
    'name',
    'useername',
    'email',
    'phone',
    'role',
    'serial_number',
    'is_active'
  ];

  protected $casts = [
    'email_verified_at' => 'datetime',
    'is_active' => 'boolean'
  ];

  public function __construct(array $attributes = [])
  {
    parent::__construct($attributes);
    $this->attributes['role'] = 'client';
  }

  // Scope to only get clients
  public function newQuery()
  {
    return parent::newQuery()->where('role', 'client');
  }

  // Relationships
  public function profile(): HasOne
  {
    return $this->hasOne(ClientProfile::class, 'user_id');
  }

  public function devices(): HasMany
  {
    return $this->hasMany(ClientDevice::class, 'client_id');
  }

  public function subscriptionRequests(): HasMany
  {
    return $this->hasMany(SubscriptionRequest::class, 'user_id');
  }

  public function subscriptions(): HasMany
  {
    return $this->hasMany(Subscription::class, 'user_id');
  }

  public function payments(): HasMany
  {
    return $this->hasMany(Payment::class, 'user_id');
  }

  // Accessors
  public function getFullNameAttribute(): string
  {
    return $this->name;
  }

  public function getActiveDevicesCountAttribute(): int
  {
    return $this->devices()->where('is_active', true)->count();
  }

  public function getActiveSubscriptionsCountAttribute(): int
  {
    return $this->subscriptionRequests()->where('status', 'active')->count();
  }

  // Helper methods
  public function hasActiveSubscription(): bool
  {
    return $this->subscriptionRequests()
      ->where('status', 'active')
      ->where('expires_at', '>', now())
      ->exists();
  }

  public function getTotalDevicesCount(): int
  {
    return $this->devices()->count();
  }

  public function getLastSubscriptionRequest()
  {
    return $this->subscriptionRequests()
      ->latest()
      ->first();
  }
}
