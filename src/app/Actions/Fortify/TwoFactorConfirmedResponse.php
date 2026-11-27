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
        $request->session()->forget('two_factor.setup');
        $via = $request->session()->pull('two_factor.via');

        $request->user()->two_factor_via = $via;
        $request->user()->save();

        $request->session()->flash('success', 'MFA Setup Complete');

        return redirect()->route('dashboard');
    }
}
