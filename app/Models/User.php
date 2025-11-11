<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'user_name',
        'email',
        'password',
        'role',
        'phone',
        'address',
        'notes',
        'access_level',
        'serial_number',
        'is_app_admin',
        'last_login_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'last_login_at' => 'datetime',
            'is_app_admin' => 'boolean',
        ];
    }

    /**
     * Check if user is admin
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Check if user is app admin (super admin)
     */
    public function isAppAdmin(): bool
    {
        return $this->is_app_admin === true;
    }

    /**
     * Check if user is client
     */
    public function isClient(): bool
    {
        return $this->role === 'client';
    }

    /**
     * Get user dashboard route based on role
     */
    public function getDashboardRoute(): string
    {
        return $this->isAdmin() ? 'admin.dashboard' : 'client.dashboard';
    }

    /**
     * Relationship to subscription requests
     */
    public function subscriptionRequests(): HasMany
    {
        return $this->hasMany(SubscriptionRequest::class);
    }

    /**
     * Relationship to subscriptions
     */
    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }

    /**
     * Relationship to payments
     */
    public function payments(): HasMany
    {
        return $this->hasMany(\App\Models\Payment::class);
    }

    /**
     * Get active subscriptions only
     */
    public function activeSubscriptions(): HasMany
    {
        return $this->subscriptions()->where('status', 'active');
    }

    /**
     * Relationship to client devices
     */
    public function clientDevices()
    {
        return $this->hasMany(ClientDevice::class);
    }

    /**
     * Get active devices only
     */
    public function activeDevices()
    {
        return $this->clientDevices()->where('status', 'active');
    }

    /**
     * Relationship to admin profile
     */
    public function adminProfile()
    {
        return $this->hasOne(AdminProfile::class);
    }

    /**
     * Relationship to client profile
     */
    public function clientProfile()
    {
        return $this->hasOne(ClientProfile::class);
    }

    /**
     * Get the appropriate profile based on user role
     */
    public function getProfile()
    {
        if ($this->isAdmin()) {
            return $this->adminProfile;
        }

        if ($this->isClient()) {
            return $this->clientProfile;
        }

        return null;
    }

    /**
     * Generate employee number for admin users
     */
    public static function generateSerialNumber($user): string
    {
        $prefix = $user->role == 'admin' ? 'EMP' : 'CUST';
        do {

            $number = $prefix . str_pad(mt_rand(1, 9999), 4, '0', STR_PAD_LEFT);
        } while (self::where('serial_number', $number)->exists());

        return $number;
    }

    /**
     * Auto-generate employee number when creating admin users
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($user) {
            $user->serial_number = self::generateSerialNumber($user);
        });
    }
}
