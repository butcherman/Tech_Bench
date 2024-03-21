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
        'pivot',
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

    public function SiteEquipment()
    {
        return $this->belongsToMany(
            CustomerEquipment::class,
            'customer_site_equipment',
            'cust_site_id',
            'cust_equip_id'
        );
    }

    public function SiteNote()
    {
        return $this->belongsToMany(
            CustomerNote::class,
            'customer_site_notes',
            'cust_site_id',
            'note_id'
        );
    }

    public function EquipmentNote()
    {
        return CustomerNote::whereIn('cust_equip_id', $this->SiteEquipment->pluck('cust_equip_id'))
            ->get();
    }

    public function GeneralNote()
    {
        return CustomerNote::where('cust_id', $this->Customer->cust_id)
            ->whereNull('cust_equip_id')
            ->doesntHave('CustomerSite')
            ->get();
    }

    public function getNotes()
    {
        return $this->SiteNote
            ->concat($this->EquipmentNote())
            ->concat($this->GeneralNote());
    }
}
