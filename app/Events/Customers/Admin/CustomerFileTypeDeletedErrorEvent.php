<?php

namespace App\Events\Customers\Admin;

use Illuminate\Queue\SerializesModels;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

use App\Models\CustomerFileType;

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
