<?php

namespace App\Events\Customers\Admin;

use App\Models\CustomerFileType;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Database\QueryException;

class CustomerFileTypeDeletedErrorEvent
{
    use Dispatchable;
    use SerializesModels;
    use InteractsWithSockets;

    public $fileType;
    public $error;

    /**
     * Create a new event instance
     */
    public function __construct(CustomerFileType $fileType, QueryException $error)
    {
        $this->fileType = $fileType;
        $this->error    = $error;
    }
}
