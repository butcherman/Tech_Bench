<?php

namespace App\Jobs\User;

use App\Models\User;
use App\Models\UserInitialize;
use App\Notifications\User\SendWelcomeEmail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;

class SendWelcomeEmailJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(protected User $user) {}

    /**
     * Update or Create a User Initialize link and email to the user
     */
    public function handle(): void
    {
        $token = Str::uuid();

        UserInitialize::firstOrCreate(
            ['username' => $this->user->username],
            ['token' => $token],
        )->update(['token' => $token]);

        Log::stack(['daily', 'auth'])->info('Sending Welcome Email to '.
            $this->user->full_name);

        Notification::send($this->user, new SendWelcomeEmail($token));
    }
}
