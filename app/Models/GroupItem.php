<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GroupItem extends Model
{
    //
    protected $table = 'group_items';
    public $timestamps = true;
    protected $fillable = [
        'group_id',
        'name',
        'description'
    ];

    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public function keys()
    {
        return $this->hasMany(Key::class);
    }
}
