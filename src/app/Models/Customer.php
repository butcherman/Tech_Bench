<?php

namespace App\Models;

use App\Observers\CustomerObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\BroadcastableModelEventOccurred;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;

#[ObservedBy([CustomerObserver::class])]
class Customer extends Model
{
    use HasFactory;
    use Searchable;
    use SoftDeletes;

    protected $primaryKey = 'cust_id';

    protected $guarded = ['updated_at', 'created_at', 'deleted_at'];

    protected $appends = ['site_count'];

    protected $hidden = [
        'updated_at',
        'created_at',
        'deleted_at',
        'deleted_reason',
        'CustomerAlert',
        'CustomerEquipment',
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
        return $this->where('slug', $value)
            ->orWhere('cust_id', $value)
            ->firstOrFail();
    }

    /***************************************************************************
     * Model Attributes
     ***************************************************************************/
    public function getSiteCountAttribute()
    {
        return $this->CustomerSite->count();
    }

    /***************************************************************************
     * Model Relationships
     ***************************************************************************/
    public function CustomerSite()
    {
        return $this->hasMany(CustomerSite::class, 'cust_id', 'cust_id');
    }

    public function CustomerAlert()
    {
        return $this->hasMany(CustomerAlert::class, 'cust_id', 'cust_id');
    }

    public function CustomerEquipment()
    {
        return $this->hasMany(CustomerEquipment::class, 'cust_id', 'cust_id');
    }

    public function CustomerContact()
    {
        return $this->hasMany(CustomerContact::class, 'cust_id', 'cust_id');
    }

    public function CustomerNote()
    {
        return $this->hasMany(CustomerNote::class, 'cust_id', 'cust_id');
    }

    public function CustomerFile()
    {
        return $this->hasMany(CustomerFile::class, 'cust_id', 'cust_id');
    }

    public function Bookmarks()
    {
        return $this->belongsToMany(
            User::class,
            'user_customer_bookmarks',
            'cust_id',
            'user_id'
        )->withTimestamps();
    }

    public function Recent()
    {
        return $this->belongsToMany(
            User::class,
            'user_customer_recents',
            'cust_id',
            'user_id'
        )->withTimestamps();
    }

    /***************************************************************************
     * Model Broadcasting
     ***************************************************************************/
    public function broadcastOn(string $event)
    {
        return match ($event) {
            'deleted', 'trashed', 'created' => [],
            default => 'customer.'.$this->slug,
        };
    }

    public function newBroadcastableModelEvent(string $event): BroadcastableModelEventOccurred
    {
        return (new BroadcastableModelEventOccurred(
            $this, $event
        ))->dontBroadcastToCurrentUser();
    }

    /***************************************************************************
     * Additional Model Methods
     ***************************************************************************/
    public function isFav(User $user)
    {
        $bookmarks = $this->Bookmarks->pluck('user_id')->toArray();

        return in_array($user->user_id, $bookmarks);
    }

    /**
     * Update the Users Recent Tech Tip activity
     */
    public function touchRecent(User $user)
    {
        $isRecent = $this->Recent->where('user_id', $user->user_id)->first();
        if ($isRecent) {
            $this->Recent()->detach($user);
        }
        $this->Recent()->attach($user);

    }
}
