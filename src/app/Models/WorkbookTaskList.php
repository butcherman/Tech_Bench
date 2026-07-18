<?php

namespace App\Models;

use Illuminate\Broadcasting\Channel;
use Illuminate\Database\Eloquent\BroadcastableModelEventOccurred;
use Illuminate\Database\Eloquent\BroadcastsEvents;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WorkbookTaskList extends Model
{
    use BroadcastsEvents;
    use HasFactory;

    /** @var array<int, string> */
    protected $guarded = ['list_id', 'created_at', 'updated_at'];

    /** @var array<int, string> */
    protected $hidden = ['list_id', 'wb_id', 'CustomerWorkbook', 'public', 'created_at'];

    /** @var array<int, string> */
    protected $with = ['WorkbookTaskListItem'];

    /** @var string */
    protected $primaryKey = 'list_id';

    protected function casts(): array
    {
        return [
            'public' => 'boolean',
            'locked' => 'boolean',
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

    public function WorkbookTaskListItem(): HasMany
    {
        return $this->hasMany(WorkbookTaskListItem::class, 'list_id', 'list_id');
    }

    /*
    |---------------------------------------------------------------------------
    | Model Broadcasting
    |---------------------------------------------------------------------------
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
