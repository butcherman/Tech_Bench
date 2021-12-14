<?php

namespace App\Http\Controllers\Auth;

use App\Events\FailedResetEmailAttempt;
use App\Events\SuccessfulResetEmailAttempt;
use Illuminate\Support\Facades\Password;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ForgotPasswordEmailRequest;

class ForgotPasswordSubmitEmailController extends Controller
{
    /**
     *  Send the user an email with a link to reset their password
     */
    public function __invoke(ForgotPasswordEmailRequest $request)
    {
        $status = Password::sendResetLink($request->only('email'));

        if($status === Password::RESET_LINK_SENT)
        {
            event(new SuccessfulResetEmailAttempt($request->email));
            return back()->with([
                'message' => __($status),
                'type'    => 'success'
            ]);
        }

        event(new FailedResetEmailAttempt($request->email));
        return back()->withErrors(['email' => __($status)]);
    }
}
