<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserCustomerBookmark extends Model
{
    use HasFactory;

    protected $guarded      = ['updated_at', 'created_at'];
    protected $hidden       = ['updated_at', 'created_at', 'Customer', 'id', 'user_id'];
    protected $appends      = ['name', 'slug'];

    public function Customer()
    {
        return $this->belongsTo(Customer::class, 'cust_id', 'cust_id');
    }

    public function getNameAttribute()
    {
        return $this->Customer->name;
    }

    public function getSlugAttribute()
    {
        return $this->Customer->slug;
    }
}
