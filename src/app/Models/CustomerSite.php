<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CustomerSite extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $primaryKey = 'cust_site_id';

    protected $guarded = ['updated_at', 'created_at', 'deleted_at'];

    protected $appends = ['is_primary', 'href'];

    protected $hidden = [
        'updated_at',
        'created_at',
        'deleted_at',
        'deleted_reason',
        'Customer',
        'href',
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
        return $this->where('site_slug', $value)
            ->orWhere('cust_site_id', $value)
            ->firstOrFail();
    }

    public function getIsPrimaryAttribute()
    {
        return $this->Customer->primary_site_id === $this->cust_site_id;
    }

    public function getHrefAttribute()
    {
        return route('customers.sites.show', [$this->Customer->slug, $this->site_slug]);
    }

    public function Customer()
    {
        return $this->belongsTo(Customer::class, 'cust_id', 'cust_id');
    }
}
