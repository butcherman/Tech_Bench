<?php

namespace App\Actions;

use App\Models\Customer;
use App\Models\CustomerContact;
use App\Models\CustomerEquipment;
use App\Models\CustomerFile;
use App\Models\CustomerNote;
use App\Models\User;

/**
 * Build a list of permissions for the user's Customer experience
 */
class BuildCustomerPermissions
{
    public function execute(User $user)
    {
        return [
            'details' => [
                'create' => $user->can('create', Customer::class),
                'update' => $user->can('update', Customer::class),
                'manage' => $user->can('manage', Customer::class),
                'delete' => $user->can('delete', Customer::class),
            ],
            'equipment' => [
                'create' => $user->can('create', CustomerEquipment::class),
                'update' => $user->can('update', CustomerEquipment::class),
                'delete' => $user->can('delete', CustomerEquipment::class),
            ],
            'contact' => [
                'create' => $user->can('create', CustomerContact::class),
                'update' => $user->can('update', CustomerContact::class),
                'delete' => $user->can('delete', CustomerContact::class),
            ],
            'notes' => [
                'create' => $user->can('create', CustomerNote::class),
                'update' => $user->can('update', CustomerNote::class),
                'delete' => $user->can('delete', CustomerNote::class),
            ],
            'files' => [
                'create' => $user->can('create', CustomerFile::class),
                'update' => $user->can('update', CustomerFile::class),
                'delete' => $user->can('delete', CustomerFile::class),
            ],
        ];
    }
}
