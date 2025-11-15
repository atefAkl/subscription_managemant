<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class PackageFeature extends Model
{
    //
    protected $table = 'package_features';
    protected $fillable = [
        'name',
        'description',
        'display_order',
        'created_by',
        'updated_by',
    ];

    public $timestamps = true;

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function editor()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function value($p_id)
    {
        return $this->hasMany(PackageFeatureValue::class)->where('service_package_id', $p_id)->first()->value;
    }

    /**
     * Boot the model.
     * Assigning the auth_user id automatically to the created_by and updated_by fields
     */
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
