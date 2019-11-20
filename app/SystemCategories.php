<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SystemCategories extends Model
{
    protected $primaryKey = 'cat_id';

    protected $fillable = ['name'];
    protected $hidden = ['created_at', 'updated_at'];

    // public function SystemTypes()
    // {
    //     return $this->hasMany('App\SystemTypes', 'cat_id', 'cat_id');
    // }

    public function SystemTypes()
    {
        return $this->hasMany('App\SystemTypes', 'cat_id', 'cat_id');
    }
}
