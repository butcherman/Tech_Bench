<?php

namespace App\Listeners\Auth;

use App\Mail\Auth\PasswordChangedMail;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class HandlePasswordResetListener
{
    /**
     * Send a notification email letting the user know that their password has
     * been changed.
     */
    public function handle(PasswordReset $event): void
    {
        Mail::to($event->user)->send(new PasswordChangedMail($event->user));

        Log::stack(['daily', 'auth'])
            ->notice('User '.$event->user->full_name.' has reset their forgotten password', [
                'User ID' => $event->user->user_id,
                'Username' => $event->user->username,
                'IP Address' => request()->ip(),
            ]);
    }
}
