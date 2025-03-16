<?php

namespace App\Actions\Admin;

use App\Mail\Admin\TestEmail;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\Mailer\Exception\TransportException;

class SendTestEmail
{
    /*
    |---------------------------------------------------------------------------
    | Send a test email to the currently authenticated user.  Message is not
    | queued so that a live result can be sent in response with any possible
    | errors that may have occurred.
    |---------------------------------------------------------------------------
    */
    public function __invoke(User $user)
    {
        try {
            Log::debug('Attempting to send test email to '.$user->email);

            Mail::to($user)->send(new TestEmail($user));

            return [true, __('admin.email.test')];
        } catch (TransportException $e) {
            Log::error('Test email failed - '.$e->getMessage());

            return [false, $e->getMessage()];
        }
    }
}
