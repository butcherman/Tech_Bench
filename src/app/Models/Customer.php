<?php

namespace App\Models;

use App\Observers\CustomerObserver;
use App\Traits\Models\HasBookmarks;
use App\Traits\Models\HasRecents;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\BroadcastableModelEventOccurred;
use Illuminate\Database\Eloquent\BroadcastsEvents;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;

#[ObservedBy([CustomerObserver::class])]
class Customer extends Model
{
    use BroadcastsEvents;
    use HasBookmarks;
    use HasFactory;
    use HasRecents;
    use Searchable;
    use SoftDeletes;

    /** @var string */
    protected $primaryKey = 'cust_id';

    /** @var array<int, string> */
    protected $guarded = ['updated_at', 'created_at', 'deleted_at'];

    /** @var array<int, string> */
    protected $hidden = [
        'updated_at',
        'created_at',
        'deleted_at',
        'deleted_reason',
        'CustomerAlert',
        'CustomerEquipment',
        'laravel_through_key',
    ];

    /** @var array<int, string> */
    protected $appends = ['site_count'];

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
    | For Route/Model binding we will use either the slug or cust_id columns
    |---------------------------------------------------------------------------
    */
    public function resolveRouteBinding($value, $field = null): Customer
    {
        return $this->where('slug', $value)
            ->orWhere('cust_id', $value)
            ->firstOrFail();
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /*
    |---------------------------------------------------------------------------
    | Model Attributes
    |---------------------------------------------------------------------------
    */
    public function siteCount(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->Sites->count(),
        );
    }

    /*
    |---------------------------------------------------------------------------
    | Model Relationships
    |---------------------------------------------------------------------------
    */
    public function Sites(): HasMany
    {
        return $this->hasMany(CustomerSite::class, 'cust_id', 'cust_id');
    }

    public function CustomerSiteList(): HasMany
    {
        return $this->hasMany(CustomerSite::class, 'cust_id', 'cust_id')
            ->withTrashed();
    }

    public function Alerts(): HasMany
    {
        return $this->hasMany(CustomerAlert::class, 'cust_id', 'cust_id');
    }

    public function Equipment(): HasMany
    {
        return $this->hasMany(CustomerEquipment::class, 'cust_id', 'cust_id');
    }

    public function Contacts(): HasMany
    {
        return $this->hasMany(CustomerContact::class, 'cust_id', 'cust_id');
    }

    public function Notes(): HasMany
    {
        return $this->hasMany(CustomerNote::class, 'cust_id', 'cust_id')
            ->orderBy('urgent', 'desc');
    }

    public function CustomerFile(): HasMany
    {
        return $this->hasMany(CustomerFile::class, 'cust_id', 'cust_id');
    }

    public function Bookmarks(): BelongsToMany
    {
        return $this->belongsToMany(
            User::class,
            'user_customer_bookmarks',
            'cust_id',
            'user_id'
        )->withTimestamps();
    }

    public function Recent(): BelongsToMany
    {
        return $this->belongsToMany(
            User::class,
            'user_customer_recents',
            'cust_id',
            'user_id'
        )->withTimestamps();
    }

    /*
    |---------------------------------------------------------------------------
    | Model Broadcasting
    |---------------------------------------------------------------------------
    */

    public function broadcastOn(string $event): array
    {
        return match ($event) {
            'deleted', 'trashed', 'created' => [],
            default => [
                new PrivateChannel('customer.' . $this->slug),
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
}
