<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserCustomerRecent extends Model
{
    use HasFactory;

    protected $guarded = ['id', 'updated_at', 'created_at'];
    protected $hidden  = ['id', 'user_id', 'created_at', 'Customer'];
    protected $appends      = ['name', 'slug'];

    public function Customer()
    {
        return $this->belongsTo(Customer::class, 'cust_id', 'cust_id');
    }

    public function getNameAttribute()
    {
        return isset($this->Customer->name) ? $this->Customer->name : null;
    }

    public function getSlugAttribute()
    {
        return isset($this->Customer->slug) ? $this->Customer->slug : null;
    }
}