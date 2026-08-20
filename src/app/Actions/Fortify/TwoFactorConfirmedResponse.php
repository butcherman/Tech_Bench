<?php

namespace App\Actions\Fortify;

use Illuminate\Http\Request;
use Laravel\Fortify\Contracts\TwoFactorConfirmedResponse as TwoFactorConfirmedResponseContract;

class TwoFactorConfirmedResponse implements TwoFactorConfirmedResponseContract
{
    /**
     * Finalize the 2FA Setup
     *
     * @param  Request  $request
     */
    public function toResponse($request)
    {
        $request->session()->flash('success', 'MFA Setup Complete');

        return redirect()->route('dashboard');
    }
}
