<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Key extends Model
{
    //
    protected $table = 'keys';
    public $timestamps = true;
    protected $fillable = [
        'key_string',
        'uuid',
        'device_id',
        'device_type',
        'group_item_id',
        'status',
        'user_id',
        'created_by',
        'updated_by',
    ];
    public function group_item()
    {
        return $this->belongsTo(GroupItem::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function statuses()
    {
        return ['active', 'new', 'blocked', 'expired'];
    }

    public function isActive()
    {
        return $this->status === 'active';
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
