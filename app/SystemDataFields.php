<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SystemDataFields extends Model
{
    protected $primaryKey = 'field_id';
    protected $fillable   = ['sys_id', 'data_type_id', 'order'];
    protected $hidden     = ['order', 'created_at', 'updated_at', 'SystemDataFieldTypes'];
    protected $appends    = ['data_field_name'];

    public function SystemDataFieldTypes()
    {
        return $this->belongsTo('App\SystemDataFieldTypes', 'data_type_id', 'data_type_id');
    }

    public function getDataFieldNameAttribute()
    {
        return $this->SystemDataFieldTypes->name;
    }
}
