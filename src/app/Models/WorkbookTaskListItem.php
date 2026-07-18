<?php

namespace App\Models;

use Illuminate\Broadcasting\Channel;
use Illuminate\Database\Eloquent\BroadcastableModelEventOccurred;
use Illuminate\Database\Eloquent\BroadcastsEvents;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class WorkbookTaskListItem extends Model
{
    use BroadcastsEvents;
    use HasFactory;
    use SoftDeletes;

    /** @var array<int, string> */
    protected $guarded = ['id', 'created_at', 'updated_at'];

    /** @var array<int, string> */
    protected $hidden = ['id', 'list_id', 'CustomerWorkbook', 'WorkbookTaskList'];

    /** @var array<int, string> */
    protected function casts(): array
    {
        return [
            'public' => 'boolean',
            'completed' => 'datetime:M d, Y',
            'created_at' => 'datetime:M d, Y',
            'updated_at' => 'datetime:M d, Y',
            'deleted_at' => 'datetime:M d, Y',
        ];
    }

    /*
    |---------------------------------------------------------------------------
    | Relationships
    |---------------------------------------------------------------------------
    */
    public function WorkbookTaskList(): BelongsTo
    {
        return $this->belongsTo(WorkbookTaskList::class, 'list_id', 'list_id');
    }

    /*
    |---------------------------------------------------------------------------
    | Model Broadcasting
    |---------------------------------------------------------------------------
    */
    public function broadcastOn(string $event): array
    {
        return [
            new Channel('workbook-task-list.'.$this->WorkbookTaskList->list_index),
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
