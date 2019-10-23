<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserPermissions extends Model
{
    protected $fillable = [
        'user_id', 'manage_users', 'run_reports', 'add_customer', 'deactivate_customer', 'use_file_links', 'create_tech_tip', 'edit_tech_tip', 'delete_tech_tip', 'create_category', 'modify_category'
    ];
    
    protected $primaryKey = 'user_id';
    
    public function User()
    {
        return $this->belongsTo('App\User', 'user_id', 'user_id');
    }
}
