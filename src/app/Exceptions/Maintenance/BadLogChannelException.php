<?php

namespace App\Exceptions\Maintenance;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

/**
 * Exception is triggered when an invalid Log Channel name is entered
 */
class BadLogChannelException extends Exception
{
    public function __construct(protected string $badChannel)
    {
        parent::__construct();
    }

    public function report(Request $request): void
    {
        Log::error('User '.$request->user()->username.
            ' tried to visit an invalid log channel - '.$this->badChannel);
    }

    public function render(): never
    {
        abort(404, 'Invalid Log Channel');
    }
}
