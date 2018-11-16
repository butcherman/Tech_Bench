<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CustomerFileTypes extends Model
{
    protected $primaryKey = 'file_type_id';
    protected $fillable = ['description'];
    
    public function CustomerFiles()
    {
        return $this->belongsTo('App\CustomerFiles', 'file_type_id', 'file_type_id');
    }
}
