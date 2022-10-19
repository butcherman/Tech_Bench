<?php

namespace App\Events\Admin;

use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use App\Models\UserRoles;

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
        $this->role        = $role;
        $this->permissions = $permissions;
    }
}
