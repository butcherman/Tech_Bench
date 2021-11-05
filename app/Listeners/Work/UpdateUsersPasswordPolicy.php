<?php

namespace App\Listeners\Work;

use App\Events\Admin\PasswordPolicyUpdatedEvent;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class UpdateUsersPasswordPolicy
{
    /**
     * Handle the event
     */
    public function handle(PasswordPolicyUpdatedEvent $event)
    {
        $expiryDays = $event->expiryDays;

        //  If the number of days was set to 0, remove all password expiration dates
        if($expiryDays == 0)
        {
            User::whereNotNull('password_expires')->update([
                'password_expires' => null,
            ]);
            Log::info('Password Expiration Dates have been removed for all active users');
        }
        else
        {
            $newExpire = Carbon::now()->addDays($expiryDays);
            User::where('password_expires', '>', Carbon::now())->orWhereNull('password_expires')->update([
                'password_expires' => $newExpire,
            ]);
            Log::info('Password Expiration Dates have been updated for all active users');
        }
    }
}
