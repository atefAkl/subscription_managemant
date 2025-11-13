<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    //
    protected $table = 'groups';
    public $timestamps = true;
    protected $fillable = [
        'name',
        'description'
    ];

    public function group_items()
    {
        return $this->hasMany(GroupItem::class, 'group_id', 'id');
    }

    public function keys()
    {
        return $this->group_items->keys;
    }
}
