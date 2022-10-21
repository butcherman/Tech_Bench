<?php

namespace App\Listeners\Admin;

use App\Events\Admin\UserRoleUpdatedEvent;
use App\Models\UserRolePermissions;

class UpdateRolePermissions
{
    /**
     * Handle the event
     */
    public function handle(UserRoleUpdatedEvent $event)
    {
        foreach($event->permissions as $perm => $value)
        {
            UserRolePermissions::where('role_id', $event->role->role_id)
                ->where('perm_type_id', str_replace('type-', '', $perm))
                ->update([
                    'allow' => $value,
                ]);
        }
    }
}
