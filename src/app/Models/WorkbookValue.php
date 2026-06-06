<?php

namespace App\Models;

use App\Observers\WorkbookValueObserver;
use Illuminate\Broadcasting\Channel;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\BroadcastableModelEventOccurred;
use Illuminate\Database\Eloquent\BroadcastsEvents;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[ObservedBy([WorkbookValueObserver::class])]
class WorkbookValue extends Model
{
    use BroadcastsEvents;
    use HasFactory;

    /** @var array<int, string> */
    protected $guarded = ['id', 'created_at', 'updated_at'];

    /** @var array<int, string> */
    protected $hidden = ['id', 'wb_id', 'CustomerWorkbook', 'protected', 'created_at'];

    protected function casts(): array
    {
        return [
            'protected' => 'boolean',
            'created_at' => 'datetime:M d, Y',
            'updated_at' => 'datetime:M d, Y',
        ];
    }

    public function getRouteKeyName(): string
    {
        return 'index';
    }

    /*
    |---------------------------------------------------------------------------
    | Relationships
    |---------------------------------------------------------------------------
    */
    public function CustomerWorkbook(): BelongsTo
    {
        return $this->belongsTo(CustomerEquipmentWorkbook::class, 'wb_id', 'wb_id');
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
        return [
            new Channel('equipment-workbook.'.$this->CustomerWorkbook->wb_hash),
        ];
    }

    public function newBroadcastableModelEvent(string $event): BroadcastableModelEventOccurred
    {

        return (new BroadcastableModelEventOccurred(
            $this,
            $event
        ))->dontBroadcastToCurrentUser();
    }
}
