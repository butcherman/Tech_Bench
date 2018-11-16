<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SystemCustDataTypes extends Model
{
    protected $primaryKey = 'data_type_id';
    protected $fillable = ['name'];
    
    public function systemCustDataFields()
    {
        return $this->belongsTo('App\systemCustDataFields', 'data_type_id');
    }
}
