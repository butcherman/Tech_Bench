<?php

namespace App\Jobs\User;

use App\Actions\User\BuildUserSettings;
use App\Facades\CacheData;
use App\Models\User;
use App\Models\UserSetting;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class CreateUserSettingsJob implements ShouldQueue
{
    use Queueable;

    public function __construct(protected User $user) {}

    /*
    |---------------------------------------------------------------------------
    | Create a database table entry of user settings for the new user. By
    | default, all values are set to true.
    |---------------------------------------------------------------------------
    */
    public function handle(BuildUserSettings $svc): void
    {
        Log::info('Building User Settings for New User ' . $this->user->full_name);

        $svc($this->user);
    }
}
