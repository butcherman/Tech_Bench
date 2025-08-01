<?php

namespace App\Jobs\User;

use App\Mail\User\UserWelcomeEmail;
use App\Models\User;
use App\Models\UserInitialize;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

/*
|-------------------------------------------------------------------------------
| Create a link that allows the user to finish setting up their account and
| email it to them.
|-------------------------------------------------------------------------------
*/

class SendWelcomeEmailJob implements ShouldQueue
{
    use Queueable;

    /**
     * @codeCoverageIgnore
     */
    public function __construct(protected User $user) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $token = Str::uuid();

        UserInitialize::updateOrCreate(
            ['username' => $this->user->username],
            ['token' => $token]
        );

        Mail::to($this->user)->send(new UserWelcomeEmail($this->user, $token));
    }
}
