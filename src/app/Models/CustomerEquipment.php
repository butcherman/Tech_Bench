<?php

namespace App\Models;

use App\Observers\CustomerEquipmentObserver;
use App\Traits\CustomerBroadcastingTrait;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\BroadcastableModelEventOccurred;
use Illuminate\Database\Eloquent\BroadcastsEvents;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Prunable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Log;

#[ObservedBy([CustomerEquipmentObserver::class])]
class CustomerEquipment extends Model
{
    use BroadcastsEvents;

    // use CustomerBroadcastingTrait;
    use HasFactory;
    use Prunable;
    use SoftDeletes;

    /** @var string */
    protected $primaryKey = 'cust_equip_id';

    /** @var array<int, string> */
    protected $guarded = ['updated_at', 'created_at'];

    /** @var array<int, string> */
    protected $hidden = [
        'deleted_at',
        'created_at',
        'updated_at',
        'EquipmentType',
    ];

    /** @var array<int, string> */
    protected $appends = ['equip_name'];

    /*
    |---------------------------------------------------------------------------
    | Model Casting
    |---------------------------------------------------------------------------
    */
    protected function casts(): array
    {
        return [
            'deleted_at' => 'datetime:M d, Y',
        ];
    }

    /*
    |---------------------------------------------------------------------------
    | Model Attributes
    |---------------------------------------------------------------------------
    */
    public function equipName(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->EquipmentType->name
        );
    }

    /*
    |---------------------------------------------------------------------------
    | Model Relationships
    |---------------------------------------------------------------------------
    */
    public function EquipmentType(): BelongsTo
    {
        return $this->belongsTo(EquipmentType::class, 'equip_id', 'equip_id');
    }

    public function Customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'cust_id', 'cust_id');
    }

    public function CustomerSite(): BelongsToMany
    {
        return $this->belongsToMany(
            CustomerSite::class,
            'customer_site_equipment',
            'cust_equip_id',
            'cust_site_id'
        );
    }

    public function CustomerNote(): HasMany
    {
        return $this->hasMany(
            CustomerNote::class,
            'cust_equip_id',
            'cust_equip_id'
        );
    }

    public function CustomerFile(): HasMany
    {
        return $this->hasMany(
            CustomerFile::class,
            'cust_equip_id',
            'cust_equip_id'
        );
    }

    public function CustomerEquipmentData(): HasMany
    {
        return $this->hasMany(
            CustomerEquipmentData::class,
            'cust_equip_id',
            'cust_equip_id'
        );
    }

    /*
    |---------------------------------------------------------------------------
    | Model Broadcasting
    |---------------------------------------------------------------------------
    */
    public function broadcastOn(string $event): array
    {
        $siteChannels = $this->getSiteChannels(
            $this->CustomerSite->pluck('site_slug')->toArray()
        );

        $allChannels = array_merge(
            $siteChannels,
            [
                new PrivateChannel('customer.'.$this->Customer->slug),
                new PrivateChannel('customer-equipment.'.$this->cust_equip_id),
            ]
        );

        return match ($event) {
            'trashed', 'deleted' => [],
            default => $allChannels,
        };
    }

    public function newBroadcastableModelEvent(string $event): BroadcastableModelEventOccurred
    {

        return (new BroadcastableModelEventOccurred(
            $this,
            $event
        ))->dontBroadcastToCurrentUser();
    }

    /*
    |---------------------------------------------------------------------------
    | Prune soft deleted models after 90 days
    |---------------------------------------------------------------------------
    */
    public function prunable(): Builder
    {
        Log::debug('Calling Prune Customer Equipment');

        if (config('customer.auto_purge')) {
            return static::whereDate('deleted_at', '<=', now()->subDays(90));
        }

        return static::whereCustEquipId(0);
    }
}
