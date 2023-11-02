<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserCustomerBookmark extends Model
{
    protected $guarded = ['updated_at', 'created_at'];

    protected $hidden = ['updated_at', 'created_at', 'Customer', 'id', 'user_id'];

    protected $appends = ['name', 'slug'];

    /**
     * Each bookmark is tied to a customer ID
     */
    public function Customer()
    {
        return $this->belongsTo(Customer::class, 'cust_id', 'cust_id');
    }

    /**
     * Get the name of the customer bookmarked
     */
    public function getNameAttribute()
    {
        return isset($this->Customer->name) ? $this->Customer->name : null;
    }

    /**
     * Get the slug for the link
     */
    public function getSlugAttribute()
    {
        return isset($this->Customer->slug) ? $this->Customer->slug : null;
    }
}
