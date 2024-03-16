<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $primaryKey = 'cust_id';

    protected $guarded = ['updated_at', 'created_at', 'deleted_at'];

    protected $appends = ['site_count'];

    protected $hidden = [
        'updated_at',
        'created_at',
        'deleted_at',
        'deleted_reason',
        'CustomerAlert',
        'CustomerEquipment',
    ];

    protected $casts = [
        'created_at' => 'datetime:M d, Y',
        'updated_at' => 'datetime:M d, Y',
        'deleted_at' => 'datetime:M d, Y',
    ];

    /**
     * For Route/Model binding we will use either the slug or cust_id columns
     */
    public function resolveRouteBinding($value, $field = null)
    {
        return $this->where('slug', $value)->orWhere('cust_id', $value)
            ->firstOrFail();
    }

    public function getSiteCountAttribute()
    {
        return $this->CustomerSite->count();
    }

    /**
     * Model Relationships
     */
    public function CustomerSite()
    {
        return $this->hasMany(CustomerSite::class, 'cust_id', 'cust_id');
    }

    public function CustomerAlert()
    {
        return $this->hasMany(CustomerAlert::class, 'cust_id', 'cust_id');
    }

    public function CustomerEquipment()
    {
        return $this->hasMany(CustomerEquipment::class, 'cust_id', 'cust_id');
    }

    public function CustomerContact()
    {
        return $this->hasMany(CustomerContact::class, 'cust_id', 'cust_id');
    }

    public function CustomerNote()
    {
        return $this->hasMany(CustomerNote::class, 'cust_id', 'cust_id');
    }
}
