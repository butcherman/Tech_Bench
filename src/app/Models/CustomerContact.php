<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Prunable;
use Illuminate\Database\Eloquent\SoftDeletes;

class CustomerContact extends Model
{
    use HasFactory;
    use Prunable;
    use SoftDeletes;

    protected $primaryKey = 'cont_id';

    protected $guarded = ['cont_id', 'created_at', 'updated_at'];

    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];

    protected $with = ['CustomerContactPhone', 'CustomerSite'];

    protected $casts = [
        'deleted_at' => 'datetime:M d, Y',
        'local' => 'boolean',
        'decision_maker' => 'boolean',
    ];

    /**
     * Each Contact can be assigned to multiple Customer Sites
     */
    public function CustomerSite()
    {
        return $this->belongsToMany(
            CustomerSite::class,
            'customer_site_contacts',
            'cont_id',
            'cust_site_id'
        );
    }

    /**
     * Each customer contact can have several phone numbers attached
     */
    public function CustomerContactPhone()
    {
        return $this->hasMany(CustomerContactPhone::class, 'cont_id', 'cont_id');
    }

    /**
     * Automatically remove soft deleted models after 90 days
     */
    public function prunable()
    {
        if (config('customer.auto_purge')) {
            return static::whereDate('deleted_at', '<=', now()->subDays(90));
        }

        return static::whereContId(0);
    }
}
