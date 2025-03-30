<?php

namespace App\Notifications\Maintenance\Backup;

use App\Models\User;
use Illuminate\Notifications\Notifiable as NotifiableTrait;
use Spatie\Backup\Config\Config;
use Spatie\Backup\Notifications\Notifiable;

class BackupNotifiable extends Notifiable
{
    public function routeNotificationForMail(): string|array
    {
        $installerList = User::where('role_id', 1)->get()->pluck('email')->toArray();

        return $installerList;
    }
}
