<?php

namespace App\Models;

use App\Observers\CustomerNoteObserver;
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
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Log;

#[ObservedBy([CustomerNoteObserver::class])]
class CustomerNote extends Model
{
    use BroadcastsEvents;
    use CustomerBroadcastingTrait;
    use HasFactory;
    use Prunable;
    use SoftDeletes;

    /** @var string */
    protected $primaryKey = 'note_id';

    /** @var array<int, string> */
    protected $guarded = ['note_id', 'updated_at', 'created_at'];

    /** @var array<int, string> */
    protected $with = ['CustomerEquipment'];

    protected $appends = ['author', 'updated_author'];

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
            'urgent' => 'boolean',
        ];
    }

    /*
    |---------------------------------------------------------------------------
    | Model Attributes
    |---------------------------------------------------------------------------
    */
    public function author(): Attribute
    {
        return Attribute::make(
            get: fn () => User::withTrashed()
                ->find($this->created_by)
                ->full_name
                ?? 'unknown'
        );
    }

    public function updatedAuthor(): ?Attribute
    {
        return Attribute::make(
            get: fn () => $this->updated_by
                ? User::withTrashed()
                    ->find($this->updated_by)
                    ->full_name
                ?? 'unknown'
                : null,
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

    public function CustomerSite(): BelongsToMany
    {
        return $this->belongsToMany(
            CustomerSite::class,
            'customer_site_notes',
            'note_id',
            'cust_site_id'
        );
    }

    public function CustomerEquipment(): BelongsTo
    {
        return $this->belongsTo(
            CustomerEquipment::class,
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

        if ($this->cust_equip_id) {
            $siteChannels[] = new PrivateChannel(
                'customer-equipment.'.$this->cust_equip_id
            );
        }

        $allChannels = array_merge(
            $siteChannels,
            [new PrivateChannel('customer.'.$this->Customer->slug)]
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
        Log::debug('Calling Prune Customer Notes');

        if (config('customer.auto_purge')) {
            return static::whereDate('deleted_at', '<=', now()->subDays(90));
        }

        return static::whereNoteId(0);
    }
}
