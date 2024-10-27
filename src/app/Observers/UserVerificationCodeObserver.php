<?php

namespace App\Observers;

use App\Mail\Auth\VerificationCodeMail;
use App\Models\UserVerificationCode;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class UserVerificationCodeObserver
{
    /**
     * Handle the UserVerificationCode "created" event.
     */
    public function created(UserVerificationCode $userVerificationCode): void
    {
        Log::info(
            'Generated New User Verification Code',
            $userVerificationCode->load('User')->toArray()
        );

        // Email the code to the user
        Mail::to($userVerificationCode->user)
            ->send(new VerificationCodeMail($userVerificationCode));
    }

    /**
     * Handle the UserVerificationCode "updated" event.
     */
    public function updated(UserVerificationCode $userVerificationCode): void
    {
        Log::info(
            'Generated New User Verification Code',
            $userVerificationCode->load('User')->toArray()
        );

        // Email the code to the user
        Mail::to($userVerificationCode->user)
            ->send(new VerificationCodeMail($userVerificationCode));
    }

    /**
     * Handle the UserVerificationCode "deleted" event.
     */
    public function deleted(UserVerificationCode $userVerificationCode): void
    {
        Log::debug(
            'Expired Verification Code Deleted',
            $userVerificationCode->toArray()
        );
    }
}
