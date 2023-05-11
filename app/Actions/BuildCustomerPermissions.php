<?php

namespace App\Actions;

use App\Models\CustomerContact;
use App\Models\CustomerEquipment;
use App\Models\CustomerNote;

class BuildCustomerPermissions
{
    public function execute($customer, $user)
    {
        return [
            'details' => [
                'create' => $user->can('create', $customer),
                'update' => $user->can('update', $customer),
                'manage' => $user->can('manage', $customer),
                'delete' => $user->can('delete', $customer),
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
            ]
        ];
    }
}
