<?php

namespace App\Exceptions\TechTip;

use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;

class TechTipNotFoundException extends Exception
{
    /*
    |---------------------------------------------------------------------------
    | Exception notes that a Tech Tip slug or Tech Tip ID does not exist in
    | the database.  A custom Tech Tip not found page will be rendered.
    |---------------------------------------------------------------------------
    */
    public function report(): void
    {
        Log::warning('Unable to find request customer page', [
            'user' => request()->user()->username,
            'path' => request()->path(),
        ]);
    }

    public function render(): RedirectResponse
    {
        return redirect(route('tech-tips.not-found'));
    }
}
