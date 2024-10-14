<?php

namespace App\Exceptions\TechTips;

use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

/**
 * Exception is triggered when a user tries to flag a comment multiple times
 */
class CommentFlaggedAlreadyException extends Exception
{
    public function __construct(protected Request $request)
    {
        parent::__construct();
    }

    public function report(): void
    {
        Log::notice(
            'User '.$this->request->user()->username.' is re-submitting a Tech Tip Comment as flagged'
        );
    }

    public function render(): RedirectResponse
    {
        return back()->with('warning', 'You have already flagged this comment');
    }
}
