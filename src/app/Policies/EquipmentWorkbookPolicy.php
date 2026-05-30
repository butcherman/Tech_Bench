<?php

namespace App\Policies;

use App\Models\EquipmentWorkbook;
use App\Models\User;
use App\Traits\AllowTrait;
use Illuminate\Auth\Access\Response;

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
