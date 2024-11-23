<?php

namespace App\Models;

use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Database\Eloquent\BroadcastableModelEventOccurred;
use Illuminate\Database\Eloquent\BroadcastsEvents;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class CustomerEquipmentData extends Model
{
    use BroadcastsEvents;
    use HasFactory;

    /** @var array<int, string> */
    protected $guarded = ['id', 'created_at', 'updated_at'];

    /** @var array<int, string> */
    protected $hidden = [
        'cust_equip_id',
        'field_id',
        'created_at',
        'updated_at',
        'DataField',
    ];

    /** @var array<int, string> */
    protected $appends = ['field_name', 'order'];

    /*
    |---------------------------------------------------------------------------
    | Model Attributes
    |---------------------------------------------------------------------------
    */

    public function fieldName(): Attribute
    {
        // The name of the field this value data belongs to
        return Attribute::make(
            get: fn () => $this->DataFieldType->name
        );
    }

    public function order(): Attribute
    {
        // The order that the data field should be in
        return Attribute::make(
            get: fn () => $this->DataField->order
        );
    }

    /*
    |---------------------------------------------------------------------------
    | Model Relationships
    |---------------------------------------------------------------------------
    */
    public function DataField(): HasOne
    {
        return $this->hasOne(DataField::class, 'field_id', 'field_id');
    }

    public function DataFieldType(): HasOneThrough
    {
        return $this->hasOneThrough(
            DataFieldType::class,
            DataField::class,
            'field_id',
            'type_id',
            'field_id',
            'type_id'
        );
    }

    /*
    |---------------------------------------------------------------------------
    | Model Broadcasting
    |---------------------------------------------------------------------------
    */
    public function broadcastOn(string $event): array
    {
        return match ($event) {
            // @codeCoverageIgnoreStart
            'trashed', 'deleted' => [],
            // @codeCoverageIgnoreEnd
            default => [
                new PrivateChannel('customer-equipment.'.$this->cust_equip_id),
            ],
        };
    }

    public function newBroadcastableModelEvent(string $event): BroadcastableModelEventOccurred
    {
        return (new BroadcastableModelEventOccurred(
            $this, $event
        ))->dontBroadcastToCurrentUser();
    }
}
