<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SystemDataFieldTypes extends Model
{
    protected $primaryKey = 'data_type_id';
    protected $fillable = ['name'];
    protected $hidden = ['data_type_id', 'created_at', 'updated_at'];

    // public function systemCustDataFields()
    // {
    //     return $this->belongsTo('App\systemCustDataFields', 'data_type_id');
    // }
}
