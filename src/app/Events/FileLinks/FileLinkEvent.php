<?php

namespace App\Events\FileLinks;

use App\Enum\CrudAction;
use App\Models\FileLink;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class FileLinkEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public string $action;

    /**
     * Create a new event instance.
     */
    public function __construct(public FileLink $link, CrudAction $action)
    {
        $this->action = $action->name;

        Log::debug('File Link Event called', [
            'file_link' => $link->toArray(),
            'crud_action' => $this->action,
        ]);

    }
}
