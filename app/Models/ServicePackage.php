<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServicePackage extends Model
{
    //
    protected $table = 'service_packages';
    protected $fillable = [
        'name',
        'description',
        'price',
        'duration',
        'duration_unit',
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

    public function values()
    {
        return $this->hasMany(PackageFeatureValue::class);
    }

    public function features()
    {
        return $this->belongsToMany(PackageFeature::class);
    }
}
