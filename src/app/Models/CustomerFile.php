<?php

namespace App\Models;

use App\Observers\CustomerFileObserver;
use App\Traits\CustomerBroadcastingTrait;
use App\Traits\Models\HasUser;
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
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Log;

#[ObservedBy([CustomerFileObserver::class])]
class CustomerFile extends Model
{
    use BroadcastsEvents;
    use CustomerBroadcastingTrait;
    use HasFactory;
    use HasUser;
    use Prunable;
    use SoftDeletes;

    /** @var string */
    protected $primaryKey = 'cust_file_id';

    /** @var array<int, string> */
    protected $guarded = ['cust_file_id', 'created_at', 'updated_at'];

    /** @var array<int, string> */
    protected $hidden = [
        'cust_id',
        'updated_at',
        'user_id',
        'deleted_at',
        'CustomerFileType',
        'CustomerEquipment',
        'user',
    ];

    /** @var array<int, string> */
    protected $appends = [
        'uploaded_by',
        'file_type',
        'equip_name',
        'created_stamp',
    ];

    /** @var array<int, string> */
    protected $with = ['CustomerSite'];

    /*
    |---------------------------------------------------------------------------
    | Model Casting
    |---------------------------------------------------------------------------
    */
    public function casts(): array
    {
        return [
            'created_at' => 'datetime:M d, Y',
            'updated_at' => 'datetime:M d, Y',
            'deleted_at' => 'datetime:M d, Y',
        ];
    }

    /*
    |---------------------------------------------------------------------------
    | Model Attributes
    |---------------------------------------------------------------------------
    */
    public function fileType(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->CustomerFileType->description
        );
    }

    public function createdStamp(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->created_at
        );
    }

    public function href(): Attribute
    {
        return Attribute::make(
            get: fn () => route(
                'download',
                [$this->file_id, $this->FileUpload->file_name]
            )
        );
    }

    public function uploadedBy(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->user->full_name
        );
    }

    public function equipName(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->CustomerEquipment
                ? $this->CustomerEquipment->equip_name
                : null
        );
    }

    /*
    |---------------------------------------------------------------------------
    | Model Relationships
    |---------------------------------------------------------------------------
    */
    public function FileUpload(): BelongsTo
    {
        return $this->belongsTo(FileUpload::class, 'file_id', 'file_id');
    }

    public function CustomerSite(): BelongsToMany
    {
        return $this->belongsToMany(
            CustomerSite::class,
            'customer_site_files',
            'cust_file_id',
            'cust_site_id'
        );
    }

    public function CustomerFileType(): HasOne
    {
        return $this->hasOne(
            CustomerFileType::class,
            'file_type_id',
            'file_type_id'
        );
    }

    public function Customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'cust_id', 'cust_id');
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
            $siteChannels[] = new PrivateChannel('customer-equipment.'.$this->cust_equip_id);
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
            $this, $event
        ))->dontBroadcastToCurrentUser();
    }

    /*
    |---------------------------------------------------------------------------
    | Prune soft deleted models after 90 days
    |---------------------------------------------------------------------------
    */
    public function prunable(): Builder
    {
        Log::debug('Calling Prune Customer Files');

        if (config('customer.auto_purge')) {
            return static::whereDate('deleted_at', '<=', now()->subDays(90));
        }

        return static::whereFileId(0);
    }
}
