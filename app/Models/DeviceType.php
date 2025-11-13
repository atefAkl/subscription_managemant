<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class DeviceType extends Model
{
    //
    protected $fillable = [
        'model',
        'device_type',
        'created_by',
        'updated_by'
    ];

    public $timestamps = true;

    public function device_icon()
    {
        switch ($this->device_type) {
            case 'iPhone':
                return 'fa-solid fa-mobile-screen-button  fa-5x';
            case 'iPad':
                return 'fa-solid fa-tablet-screen-button fa-5x';
            case 'Mac':
                return 'fa-solid fa-laptop fa-5x';
            case 'Apple Watch':
                return 'fa-solid fa-clock fa-5x';
            case 'Apple TV':
                return 'fa-solid fa-tv fa-5x';
            default:
                return 'fa-solid fa-apple fa-5x';
        }
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
