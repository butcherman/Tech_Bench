<?php

namespace App\Listeners\Admin;

use App\Events\Admin\UserRoleCreatedEvent;
use App\Models\UserRolePermissions;

class CreateRolePermissions
{
    /**
     * Handle the event
     */
    public function handle(UserRoleCreatedEvent $event)
    {
        foreach($event->permissions as $perm => $value)
        {
            UserRolePermissions::create([
                'role_id'      => $event->role->role_id,
                'perm_type_id' => str_replace('type-', '', $perm),
                'allow'        => $value,
            ]);
        }
    }
}
