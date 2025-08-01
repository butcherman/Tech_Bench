<?php

namespace App\Exceptions\Misc;

use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class FeatureDisabledException extends Exception
{
    /*
    |---------------------------------------------------------------------------
    | Exception notes that someone tried to access a feature that is currently
    | disabled.
    |---------------------------------------------------------------------------
    */
    public function report(): void
    {
        $user = Auth::check() ? Auth::user()->username : request()->ip();

        Log::notice($user.' tried to access a disabled feature', [
            'feature' => $this->getMessage(),
        ]);
    }

    public function render(): never
    {
        abort(404);
    }
}
