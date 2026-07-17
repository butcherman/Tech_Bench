<?php

namespace App\Models;

use Illuminate\Broadcasting\Channel;
use Illuminate\Database\Eloquent\BroadcastableModelEventOccurred;
use Illuminate\Database\Eloquent\BroadcastsEvents;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkbookTaskListItem extends Model
{
    use BroadcastsEvents;
    use HasFactory;

    /** @var array<int, string> */
    protected $guarded = ['id', 'created_at', 'updated_at'];

    /** @var array<int, string> */
    protected $hidden = ['id', 'wb_id', 'CustomerWorkbook', 'public', 'created_at'];

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
