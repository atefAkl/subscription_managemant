<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PackagePrice extends Model
{
    //

    protected $table = 'package_prices';
    protected $fillable = [
        'package_id',
        'price',
        'currency',
        'duration',
        'duration_unit',
        'discount',
        'discount_type',
        'discount_start_date',
        'discount_end_date',
        'is_active',
    ];
}
