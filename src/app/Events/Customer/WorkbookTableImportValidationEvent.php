<?php

namespace App\Events\Customer;

use App\Models\CustomerEquipmentWorkbook;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class WorkbookTableImportValidationEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(
        protected CustomerEquipmentWorkbook $workbook,
        public string $tableIndex,
        public array $validationData
    ) {}

    /**
     * Get the channels the event should broadcast on.
     */
    public function broadcastOn(): array
    {
        Log::debug('Broadcasting Workbook Table Import Validation Event', [
            'channel' => 'workbook-import.'.$this->workbook->wb_hash,
        ]);

        return [
            new Channel('workbook-import.'.$this->workbook->wb_hash),
        ];
    }

    public function broadcastAs(): string
    {
        return 'WorkbookTableImportValidationEvent';
    }
}
