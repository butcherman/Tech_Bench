<?php

namespace App\Events\Customer;

use App\Models\CustomerEquipmentWorkbook;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class WorkbookTableRowDeletedEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(
        protected CustomerEquipmentWorkbook $workbook,
        public string $tableIndex,
        public string $rowIndex
    ) {}

    /**
     * Get the channels the event should broadcast on.
     */
    public function broadcastOn(): array
    {
        return [
            new Channel(
                'equipment-workbook.'.
                $this->workbook->wb_hash.
                '.'.
                $this->tableIndex
            ),
        ];
    }

    /**
     * Get the name that the event will broadcast as.
     */
    public function broadcastAs(): string
    {
        return 'WorkbookTableRowDeleted';
    }
}
