<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use PhpParser\Node\Expr\Cast\Double;

class Key extends Model
{
    //
    protected $table = 'ss_keys';
    public $timestamps = true;
    protected $fillable = [
        'key_string',
        'uuid',
        'device_id',
        'device_type_id',
        'group_item_id',
        'status',
        'user_id',
        'created_by',
        'updated_by',
        'activated_at',
        'period',
    ];
    public function group_item()
    {
        return $this->belongsTo(GroupItem::class);
    }

    public static function generateKeyString()
    {   // generate random string of 36 chars with only uppercase and lowercase English letters
        do {
            $key = Str::random(36);
        } while (self::where('key_string', $key)->exists());
        return $key;
    }

    public function client()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public static function statuses()
    {
        return ['active', 'new', 'blocked', 'expired'];
    }

    public function remainingDays()
    {
        $period = $this->period === 'week' ? 7 : ($this->period === 'month' ? 30 : 365);
        $remDays = $period;
        if ($this->isActive()) {
            $remDays = Carbon::parse($this->activated_at)->diffInDays(now());
            $remDays = $period - $remDays;
        }
        return round((float) $remDays, 2);
    }
    public function isActive()
    {
        return $this->status === 'active';
    }

    public function groupItem()
    {
        return $this->belongsTo(GroupItem::class);
    }

    public function isBlocked()
    {
        return $this->status === 'blocked';
    }

    public function isExpired()
    {
        return $this->status === 'expired';
    }

    public function isNew()
    {
        return $this->status === 'new';
    }

    public function activate()
    {
        $this->status = 'active';
        $this->save();
    }

    public function block()
    {
        $this->status = 'blocked';
        $this->save();
    }

    public function setExpired()
    {
        $this->status = 'expired';
        $this->save();
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->created_by = Auth::check() ? Auth::id() : null;
            $model->updated_by = Auth::check() ? Auth::id() : null;
        });

        static::updating(function ($model) {
            $model->updated_by = Auth::check() ? Auth::id() : null;
        });
    }
}
