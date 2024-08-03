<?php

namespace App\Exceptions\TechTips;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CommentFlaggedAlreadyException extends Exception
{
    public function __construct(protected Request $request)
    {
        //
    }

    public function report()
    {
        Log::stack(['tip', 'daily'])
            ->notice(
                'User ' . $this->request->user()->username . ' is re-submitting a Tech Tip Comment as flagged'
            );
    }

    public function render()
    {
        return back()->with('warning', 'You have already flagged this comment');
    }
}
