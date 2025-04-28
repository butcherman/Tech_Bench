<?php

namespace App\Models;

use App\Observers\CustomerSiteObserver;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\BroadcastableModelEventOccurred;
use Illuminate\Database\Eloquent\BroadcastsEvents;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

#[ObservedBy([CustomerSiteObserver::class])]
class CustomerSite extends Model
{
    use BroadcastsEvents;
    use HasFactory;
    use SoftDeletes;

    /** @var string */
    protected $primaryKey = 'cust_site_id';

    /** @var array<int, string> */
    protected $guarded = ['updated_at', 'created_at', 'deleted_at'];

    /** @var array<int, string> */
    protected $hidden = [
        'updated_at',
        'created_at',
        'deleted_at',
        'deleted_reason',
        'Customer',
        'pivot',
    ];

    /** @var array<int, string> */
    protected $appends = ['is_primary'];

    /*
    |---------------------------------------------------------------------------
    | Model Casting
    |---------------------------------------------------------------------------
    */
    protected function casts(): array
    {
        return [
            'created_at' => 'datetime:M d, Y',
            'updated_at' => 'datetime:M d, Y',
            'deleted_at' => 'datetime:M d, Y',
        ];
    }

    /*
    |---------------------------------------------------------------------------
    | For Route/Model binding use either the site_slug or cust_site_id columns
    |---------------------------------------------------------------------------
    */
    public function resolveRouteBinding($value, $field = null): CustomerSite
    {
        return $this->where('site_slug', $value)
            ->orWhere('cust_site_id', $value)
            ->firstOrFail();
    }

    public function getRouteKeyName(): string
    {
        return 'site_slug';
    }

    /*
    |---------------------------------------------------------------------------
    | Model Attributes
    |---------------------------------------------------------------------------
    */
    public function isPrimary(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->Customer
                && $this->Customer->primary_site_id === $this->cust_site_id,
        );
    }

    public function href(): Attribute
    {
        return Attribute::make(
            get: fn () => route('customers.sites.show', [
                $this->Customer->slug,
                $this->site_slug,
            ])
        );
    }

    /*
    |---------------------------------------------------------------------------
    | Model Relationships
    |---------------------------------------------------------------------------
    */
    public function Customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'cust_id', 'cust_id');
    }

    public function SiteEquipment(): BelongsToMany
    {
        return $this->belongsToMany(
            CustomerEquipment::class,
            'customer_site_equipment',
            'cust_site_id',
            'cust_equip_id'
        );
    }

    public function SiteContact(): BelongsToMany
    {
        return $this->belongsToMany(
            CustomerContact::class,
            'customer_site_contacts',
            'cust_site_id',
            'cont_id'
        );
    }

    public function SiteNote(): BelongsToMany
    {
        return $this->belongsToMany(
            CustomerNote::class,
            'customer_site_notes',
            'cust_site_id',
            'note_id'
        );
    }

    public function SiteFile(): BelongsToMany
    {
        return $this->belongsToMany(
            CustomerFile::class,
            'customer_site_files',
            'cust_site_id',
            'cust_file_id'
        );
    }

    /*
    |---------------------------------------------------------------------------
    | Model Broadcasting
    |---------------------------------------------------------------------------
    */

    /**
     * @codeCoverageIgnore
     */
    public function broadcastOn(string $event): array
    {
        return match ($event) {
            'deleted', 'trashed', 'created' => [
                new PrivateChannel('customer.'.$this->Customer->slug),
            ],
            default => [
                new PrivateChannel('customer.'.$this->Customer->slug),
                new PrivateChannel('customer.'.$this->site_slug),
            ],
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
    | Additional Methods
    |---------------------------------------------------------------------------
    */
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
