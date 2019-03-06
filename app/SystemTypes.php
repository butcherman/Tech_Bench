<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SystemTypes extends Model
{
    protected $primaryKey = 'sys_id';
    
    protected $fillable = ['cat_id', 'name', 'parent_id', 'folder_location'];
    
    public function systemCategories()
    {
        return $this->belongsTo('App\SystemCategories', 'cat_id');
    }
    
    public function systemFiles()
    {
        return $this->hasMany('App\SystemFiles', 'type_id', 'type_id');
    }
    
    public function systemCustDataFields()
    {
        return $this->belongsTo('App\systemCustDataFields', 'sys_id', 'sys_id');
    }
    
    public function CustomerSystems()
    {
        return $this->belongsTo('App\CustomerSystems', 'sys_id');
    }
    
    public function techTipSystems()
    {
        return $this->belongsTo('App\TechTipSystems', 'sys_id', 'sys_id');
    }
}
