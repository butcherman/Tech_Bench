<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SystemTypes extends Model
{
    protected $primaryKey = 'sys_id';
    protected $fillable = ['cat_id', 'name', 'parent_id', 'folder_location'];
    protected $hidden = ['cat_id', 'created_at', 'folder_location', 'updated_at'];

    public function SystemDataFields()
    {
        return $this->hasMany('App\SystemDataFields', 'sys_id', 'sys_id');
    }
}
