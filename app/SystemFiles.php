<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SystemFiles extends Model
{
    protected $primaryKey = 'sys_file_id';
    protected $fillable = ['sys_id', 'type_id', 'file_id', 'name', 'description', 'user_id'];
    
    public function systemFileTypes()
    {
        return $this->belongsTo('App\SystemFileTypes', 'type_id');
    }
    
    public function systemTypes()
    {
        return $this->belongsTo('App\SystemTypes', 'sys_id');
    }
    
    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }
    
    public function files()
    {
        return $this->belongsTo('App\Files', 'file_id');
    }
}
