<?php

namespace App\Policies;

use App\Models\User;
use App\Traits\AllowTrait;

class EquipmentWorkbookPolicy
{
    use AllowTrait;

    /**
     * Determine whether the user can view any models.
     */
    public function manage(User $user): bool
    {
        return $this->checkPermission($user, 'Manage Equipment Workbooks');
    }
}
