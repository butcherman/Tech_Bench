<?php

namespace App\Policies;

use App\Models\EquipmentType;
use App\Models\User;
use App\Models\UserRolePermissions;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Log;

class EquipmentTypePolicy
{
    use HandlesAuthorization;

    /*
    *   Allow anyone with "Manage Equipment" permission
    */
    public function before(User $user, $method)
    {
        $allowed = UserRolePermissions::whereRoleId($user->role_id)->whereHas('UserRolePermissionTypes', function($q)
        {
            $q->where('description', 'Manage Equipment');
        })->first();

        Log::channel('auth')->debug('User '.$user->username.' is checking Equipment Policy access to '.$method.'.  Result - '.($allowed->allow ? 'Allow' : 'Deny'));

        if($allowed->allow)
        {
            return $allowed->allow;
        }
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return false;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\EquipmentType  $equipmentType
     * @return mixed
     */
    public function update(User $user, EquipmentType $equipmentType)
    {
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\EquipmentType  $equipmentType
     * @return mixed
     */
    public function delete(User $user, EquipmentType $equipmentType)
    {
        return false;
    }
}
