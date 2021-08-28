<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Support\Facades\Password;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ForgotPasswordEmailRequest;
use Illuminate\Support\Facades\Log;

class ForgotPasswordSubmitEmailController extends Controller
{
    /**
     *  Send the user an email with a link to reset their password
     */
    public function __invoke(ForgotPasswordEmailRequest $request)
    {
        $status = Password::sendResetLink($request->only('email'));

        return $status === Password::RESET_LINK_SENT ?
            back()->with([
                'message' => $status,
                'type'    => 'success'
            ]) : back()->withErrors(['email' => $status]);
    }
}
