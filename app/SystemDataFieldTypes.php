<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SystemDataFieldTypes extends Model
{
    protected $primaryKey = 'data_type_id';
    protected $fillable   = ['name', 'hidden'];
    protected $hidden     = ['created_at', 'updated_at'];
    protected $casts      = ['hidden' => 'boolean'];

    public function SystemDataFields()
    {
        return $this->hasMany('App\SystemDataFields', 'data_type_id', 'data_type_id');
    }
}
