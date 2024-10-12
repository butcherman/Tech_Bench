<?php

namespace App\Models;

use App\Observers\CustomerContactObserver;
use Closure;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\BroadcastableModelEventOccurred;
use Illuminate\Database\Eloquent\BroadcastsEvents;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Prunable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Log;

#[ObservedBy([CustomerContactObserver::class])]
class CustomerContact extends Model
{
    use BroadcastsEvents;
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

    /***************************************************************************
     * Model Relationships
     ***************************************************************************/
    public function Customer()
    {
        return $this->belongsTo(Customer::class, 'cust_id', 'cust_id');
    }

    public function CustomerSite()
    {
        return $this->belongsToMany(
            CustomerSite::class,
            'customer_site_contacts',
            'cont_id',
            'cust_site_id'
        );
    }

    public function CustomerContactPhone()
    {
        return $this->hasMany(CustomerContactPhone::class, 'cont_id', 'cont_id');
    }

    /***************************************************************************
     * Model Broadcasting
     ***************************************************************************/
    public function broadcastOn(string $event): Closure|array
    {
        return match ($event) {
            'deleted', 'trashed' => [],
            default => function () {
                $siteList = $this->CustomerSite->pluck('site_slug')->toArray();
                array_walk($siteList, function (&$value) {
                    $value = 'customer-site.'.$value;
                });

                $siteList[] = 'customer.'.$this->Customer->slug;

                Log::debug('Broadcasting Customer Contact Event on channels - ', $siteList);

                return $siteList;
            },
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
        Log::debug('Calling Prune Customer Contacts');

        if (config('customer.auto_purge')) {
            return static::whereDate('deleted_at', '<=', now()->subDays(90));
        }

        return static::whereContId(0);
    }
}
