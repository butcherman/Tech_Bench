<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SystemFileTypes extends Model
{
    protected $primaryKey = 'type_id';
    protected $fillable = ['description'];
    
    public function systemFiles()
    {
        return $this->hasMany('App\SystemFiles', 'type_id', 'type_id');
    }
}
