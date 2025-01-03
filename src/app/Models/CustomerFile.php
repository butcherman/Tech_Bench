<?php

namespace App\Models;

use App\Observers\CustomerFileObserver;
use App\Traits\CustomerBroadcastingTrait;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\BroadcastableModelEventOccurred;
use Illuminate\Database\Eloquent\BroadcastsEvents;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Prunable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Log;

#[ObservedBy([CustomerFileObserver::class])]
class CustomerFile extends Model
{
    use BroadcastsEvents;
    use CustomerBroadcastingTrait;
    use HasFactory;
    use Prunable;
    use SoftDeletes;

    protected $primaryKey = 'cust_file_id';

    protected $guarded = ['cust_file_id', 'created_at', 'updated_at'];

    protected $hidden = [
        'cust_id',
        'updated_at',
        'user_id',
        'deleted_at',
        'CustomerFileType',
        'CustomerEquipment',
        'user',
    ];

    protected $appends = [
        'uploaded_by',
        'file_type',
        'equip_name',
        'created_stamp',
    ];

    protected $with = ['CustomerSite'];

    protected $casts = [
        'created_at' => 'datetime:M d, Y',
        'updated_at' => 'datetime:M d, Y',
        'deleted_at' => 'datetime:M d, Y',
    ];

    /***************************************************************************
     * Model Attributes
     ***************************************************************************/
    public function getFileTypeAttribute()
    {
        return $this->CustomerFileType->description;
    }

    public function getCreatedStampAttribute()
    {
        return $this->created_at;
    }

    public function getHrefAttribute()
    {
        return route('download', [$this->file_id, $this->FileUpload->file_name]);
    }

    public function getUploadedByAttribute()
    {
        return $this->user->full_name;
    }

    public function getEquipNameAttribute()
    {
        return $this->CustomerEquipment ?
            $this->CustomerEquipment->equip_name : null;
    }

    /***************************************************************************
     * Model Relationships
     ***************************************************************************/
    public function FileUpload()
    {
        return $this->belongsTo(FileUpload::class, 'file_id', 'file_id');
    }

    public function CustomerSite()
    {
        return $this->belongsToMany(
            CustomerSite::class,
            'customer_site_files',
            'cust_file_id',
            'cust_site_id'
        );
    }

    public function CustomerFileType()
    {
        return $this->hasOne(
            CustomerFileType::class,
            'file_type_id',
            'file_type_id'
        );
    }

    public function User()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id')
            ->withTrashed();
    }

    public function Customer()
    {
        return $this->belongsTo(Customer::class, 'cust_id', 'cust_id');
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

    /**
     * @codeCoverageIgnore
     */
    public function broadcastOn(string $event): array
    {
        $siteChannels = $this->getSiteChannels(
            $this->CustomerSite->pluck('site_slug')->toArray()
        );

        if ($this->cust_equip_id) {
            $siteChannels[] = new PrivateChannel('customer-equipment.'.$this->cust_equip_id);
        }

        $allChannels = array_merge(
            $siteChannels,
            [new PrivateChannel('customer.'.$this->Customer->slug)]
        );

        Log::debug(
            'Broadcasting Customer Equipment Event - Event Name - '.$event,
            $allChannels
        );

        return match ($event) {
            'trashed', 'deleted' => [],
            default => $allChannels,
        };
    }

    public function newBroadcastableModelEvent(string $event): BroadcastableModelEventOccurred
    {
        Log::debug('Calling Do Not Broadcast to Current User', $this->toArray());

        return (new BroadcastableModelEventOccurred(
            $this, $event
        ))->dontBroadcastToCurrentUser();
    }

    /***************************************************************************
     * Prune soft deleted models after 90 days
     ***************************************************************************/
    public function prunable()
    {
        Log::debug('Calling Prune Customer Files');

        if (config('customer.auto_purge')) {
            return static::whereDate('deleted_at', '<=', now()->subDays(90));
        }

        return static::whereFileId(0);
    }
}
