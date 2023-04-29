<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ForgotPasswordEmailRequest;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Password;

class ForgotPasswordSubmitEmailController extends Controller
{
    public function __construct()
    {
        //  To help prevent bots, we will not allow more than 50 login attempts within a two hour period
        $this->middleware('throttle:50,120');
    }

    /**
     *  Send the user an email with a link to reset their password
     */
    public function __invoke(ForgotPasswordEmailRequest $request)
    {
        $status = Password::sendResetLink($request->only('email'));

        if ($status === Password::RESET_LINK_SENT) {
            Log::channel('auth')->notice('A password reset email has been sent to '.$request->email);

            return back()->with('success', __($status));
        }

        Log::channel('auth')->warning('Someone has failed to enter a proper email address to request a Password Reset link', [
            'Email' => $request->email,
            'IP Address' => \Request::ip(),
        ]);

        return back()->withErrors(['email' => __($status)]);
    }
}
