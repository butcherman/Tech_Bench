<?php

namespace App\Exceptions\Maintenance;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class BadLogChannelException extends Exception
{
    public function __construct(protected string $badChannel)
    {
        parent::__construct();
    }

    public function report(Request $request)
    {
        Log::error('User '.$request->user()->username.
            ' tried to visit an invalid log channel - '.$this->badChannel);
    }

    public function render(Request $request)
    {
        abort(404, 'Invalid Log Channel');
    }
}
