<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PackageFeatureValue extends Model
{
    //
    protected $table = 'package_feature_values';
    protected $fillable = [
        'service_package_id',
        'package_feature_id',
        'value',
        'value_type'
    ];

    public $timestamps = true;

    public function package()
    {
        return $this->belongsTo(ServicePackage::class);
    }

    public function feature()
    {
        return $this->belongsTo(PackageFeature::class, 'package_feature_id', 'id');
    }
}
