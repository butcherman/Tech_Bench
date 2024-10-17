<?php

namespace App\Models;

use App\Observers\CustomerNoteObserver;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\BroadcastableModelEventOccurred;
use Illuminate\Database\Eloquent\BroadcastsEvents;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Prunable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Log;

#[ObservedBy([CustomerNoteObserver::class])]
class CustomerNote extends Model
{
    use BroadcastsEvents;
    use HasFactory;
    use Prunable;
    use SoftDeletes;

    protected $primaryKey = 'note_id';

    protected $guarded = ['note_id', 'updated_at', 'created_at'];

    protected $appends = ['author', 'updated_author'];

    protected $with = ['CustomerEquipment'];

    protected $casts = [
        'created_at' => 'datetime:M d, Y',
        'updated_at' => 'datetime:M d, Y',
        'deleted_at' => 'datetime:M d, Y',
        'urgent' => 'boolean',
    ];

    /***************************************************************************
     * Model Attributes
     ***************************************************************************/
    public function getAuthorAttribute()
    {
        return User::withTrashed()->find($this->created_by)->full_name ?? 'unknown';
    }

    public function getUpdatedAuthorAttribute()
    {
        if ($this->updated_by) {
            return User::withTrashed()->find($this->updated_by)->full_name ?? 'unknown';
        }
    }

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
            'customer_site_notes',
            'note_id',
            'cust_site_id'
        );
    }

    public function CustomerEquipment()
    {
        return $this->belongsTo(
            CustomerEquipment::class,
            'cust_equip_id',
            'cust_equip_id'
        );
    }

    /***************************************************************************
     * Model Broadcasting
     ***************************************************************************/
    public function broadcastOn(string $event): array
    {
        Log::debug('Broadcasting Customer Site Event');

        return [
            new PrivateChannel('customer.'.$this->Customer->slug),
        ];
    }

    public function newBroadcastableModelEvent(string $event): BroadcastableModelEventOccurred
    {
        Log::debug('Calling Dont Broadcast to Current User');

        return (new BroadcastableModelEventOccurred(
            $this, $event
        ))->dontBroadcastToCurrentUser();
    }

    /***************************************************************************
     * Prune soft deleted models after 90 days
     ***************************************************************************/
    public function prunable()
    {
        Log::debug('Calling Prune Customer Notes');

        if (config('customer.auto_purge')) {
            return static::whereDate('deleted_at', '<=', now()->subDays(90));
        }

        return static::whereNoteId(0);
    }
}
