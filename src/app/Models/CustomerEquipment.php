<?php

namespace App\Models;

use App\Observers\CustomerEquipmentObserver;
use App\Traits\CustomerBroadcastingTrait;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\BroadcastableModelEventOccurred;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Prunable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Log;

#[ObservedBy([CustomerEquipmentObserver::class])]
class CustomerEquipment extends Model
{
    use CustomerBroadcastingTrait;
    use HasFactory;
    use Prunable;
    use SoftDeletes;

    protected $primaryKey = 'cust_equip_id';

    protected $guarded = ['updated_at', 'created_at'];

    protected $hidden = [
        'deleted_at',
        'created_at',
        'updated_at',
        'EquipmentType',
    ];

    protected $appends = ['equip_name'];

    protected $casts = [
        'deleted_at' => 'datetime:M d, Y',
    ];

    /***************************************************************************
     * Model Attributes
     ***************************************************************************/
    public function getEquipNameAttribute()
    {
        return $this->EquipmentType->name;
    }

    /***************************************************************************
     * Model Relationships
     ***************************************************************************/
    public function EquipmentType()
    {
        return $this->belongsTo(EquipmentType::class, 'equip_id', 'equip_id');
    }

    public function Customer()
    {
        return $this->belongsTo(Customer::class, 'cust_id', 'cust_id');
    }

    public function CustomerSite()
    {
        return $this->belongsToMany(
            CustomerSite::class,
            'customer_site_equipment',
            'cust_equip_id',
            'cust_site_id'
        );
    }

    public function CustomerNote()
    {
        return $this->hasMany(CustomerNote::class, 'cust_equip_id', 'cust_equip_id');
    }

    public function CustomerFile()
    {
        return $this->hasMany(CustomerFile::class, 'cust_equip_id', 'cust_equip_id');
    }

    public function CustomerEquipmentData()
    {
        return $this->hasMany(CustomerEquipmentData::class, 'cust_equip_id', 'cust_equip_id');
    }

    /***************************************************************************
     * Model Broadcasting
     ***************************************************************************/
    public function broadcastOn(string $event)
    {
        return match ($event) {
            'deleted', 'trashed', 'created' => [],
            default => array_merge(
                $this->getCustomerChannel(),
                $this->getSiteChannelList(),
                $this->getEquipmentChannel(),
            ),
        };
    }

    public function newBroadcastableModelEvent(string $event): BroadcastableModelEventOccurred
    {
        return (new BroadcastableModelEventOccurred(
            $this, $event
        ))->dontBroadcastToCurrentUser();
    }

    /***************************************************************************
     * Prune soft deleted models after 90 days
     ***************************************************************************/
    public function prunable()
    {
        Log::debug('Calling Prune Customer Equipment');

        if (config('customer.auto_purge')) {
            return static::whereDate('deleted_at', '<=', now()->subDays(90));
        }

        return static::whereCustEquipId(0);
    }
}
