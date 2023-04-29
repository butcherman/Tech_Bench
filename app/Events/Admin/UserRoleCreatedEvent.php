<?php

namespace App\Events\Admin;

use App\Models\UserRoles;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserRoleCreatedEvent
{
    use Dispatchable;
    use SerializesModels;
    use InteractsWithSockets;

    public $role;

    public $permissions;

    /**
     * Create a new event instance
     */
    public function __construct(UserRoles $role, array $permissions)
    {
        $this->role = $role;
        $this->permissions = $permissions;
    }
}
