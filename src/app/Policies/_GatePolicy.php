<?php

namespace App\Policies;

use App\Models\User;
use App\Traits\AllowTrait;

class _GatePolicy
{
    use AllowTrait;

    /**
     * Determine if the user is allowed to see the Administration navigation link
     */
    public function adminLink(User $user): bool
    {
        return $this->seeAdminLink($user);
    }

    /**
     * Determine if the user is allowed to see the Reports navigation link
     */
    public function reportsLink(User $user): bool
    {
        return $this->seeReportLink($user);
    }

    /**
     * Determine if the user is allowed to see the Maintenance link
     */
    public function maintenanceLink(User $user): bool
    {
        return $this->maintenanceLink($user);
    }

    /**
     * Determine if the user is an Installer Level User
     */
    public function isInstaller(User $user): bool
    {
        return $user->role_id === 1;
    }
}
