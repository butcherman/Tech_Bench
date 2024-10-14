<?php

namespace App\Models;

use App\Observers\CustomerSiteObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

#[ObservedBy([CustomerSiteObserver::class])]
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

    /***************************************************************************
     * For Route/Model binding we will use either the slug or cust_id columns
     ***************************************************************************/
    public function resolveRouteBinding($value, $field = null)
    {
        return $this->where('site_slug', $value)
            ->orWhere('cust_site_id', $value)
            ->firstOrFail();
    }

    /***************************************************************************
     * Model Attributes
     ***************************************************************************/
    public function getIsPrimaryAttribute()
    {
        if ($this->Customer) {
            return $this->Customer->primary_site_id === $this->cust_site_id;
        }

        return false;
    }

    public function getHrefAttribute()
    {
        return route('customers.sites.show', [
            $this->Customer->slug,
            $this->site_slug,
        ]);
    }

    /***************************************************************************
     * Model Relationships
     ***************************************************************************/
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

    public function SiteContact()
    {
        return $this->belongsToMany(
            CustomerContact::class,
            'customer_site_contacts',
            'cust_site_id',
            'cont_id'
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

    public function SiteFile()
    {
        return $this->belongsToMany(
            CustomerFile::class,
            'customer_site_files',
            'cust_site_id',
            'cust_file_id'
        );
    }

    /***************************************************************************
     * Additional Methods
     ***************************************************************************/
    public function EquipmentNote()
    {
        return CustomerNote::whereIn(
            'cust_equip_id',
            $this->SiteEquipment->pluck('cust_equip_id')
        )->get();
    }

    public function EquipmentFile()
    {
        return CustomerFile::whereIn(
            'cust_equip_id',
            $this->SiteEquipment->pluck('cust_equip_id')
        )->get();
    }

    public function GeneralNote()
    {
        return CustomerNote::where('cust_id', $this->Customer->cust_id)
            ->whereNull('cust_equip_id')
            ->doesntHave('CustomerSite')
            ->get();
    }

    public function GeneralFile()
    {
        return CustomerFile::where('cust_id', $this->Customer->cust_id)
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

    public function getFiles()
    {
        return $this->SiteFile
            ->concat($this->EquipmentFile())
            ->concat($this->GeneralFile());
    }
}
