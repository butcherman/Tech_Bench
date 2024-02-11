<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserInitialize extends Model
{
    protected $guarded = ['created_at', 'updated_at'];

    protected $hidden = ['id', 'created_at', 'updated_at'];

    /**
     * Route Model Binding Key
     */
    public function getRouteKeyName()
    {
        return 'token';
    }

    /**
     * Model Relationships
     */
    public function User()
    {
        return $this->hasOne(User::class, 'username', 'username');
    }
}
