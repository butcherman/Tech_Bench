<?php

namespace App\Models;

use App\Observers\CustomerObserver;
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
    use HasFactory;
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
    ];

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

    /*
    |---------------------------------------------------------------------------
    | Model Attributes
    |---------------------------------------------------------------------------
    */
    protected $appends = ['site_count'];

    public function siteCount(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->CustomerSite->count(),
        );
    }

    /*
    |---------------------------------------------------------------------------
    | Model Relationships
    |---------------------------------------------------------------------------
    */
    public function CustomerSite(): HasMany
    {
        return $this->hasMany(CustomerSite::class, 'cust_id', 'cust_id');
    }

    public function CustomerAlert(): HasMany
    {
        return $this->hasMany(CustomerAlert::class, 'cust_id', 'cust_id');
    }

    public function CustomerEquipment(): HasMany
    {
        return $this->hasMany(CustomerEquipment::class, 'cust_id', 'cust_id');
    }

    public function CustomerContact(): HasMany
    {
        return $this->hasMany(CustomerContact::class, 'cust_id', 'cust_id');
    }

    public function CustomerNote(): HasMany
    {
        return $this->hasMany(CustomerNote::class, 'cust_id', 'cust_id');
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
                new PrivateChannel('customer.'.$this->slug),
            ],
        };
    }

    public function newBroadcastableModelEvent(string $event): BroadcastableModelEventOccurred
    {
        return (new BroadcastableModelEventOccurred(
            $this, $event
        ))->dontBroadcastToCurrentUser();
    }

    /*
    |---------------------------------------------------------------------------
    | Additional Model Methods
    |---------------------------------------------------------------------------
    */

    /**
     * Determine if the user requesting the page has bookmarked customer
     */
    public function isFav(User $user)
    {
        $bookmarks = $this->Bookmarks->pluck('user_id')->toArray();

        return in_array($user->user_id, $bookmarks);
    }

    /**
     * Update the Users Recent Tech Tip activity
     */
    public function touchRecent(User $user): void
    {
        $isRecent = $this->Recent->where('user_id', $user->user_id)->first();
        if ($isRecent) {
            $isRecent->touch();
        } else {
            $this->Recent()->attach($user);
        }
    }
}
