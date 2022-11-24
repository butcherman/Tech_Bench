<?php

namespace App\Actions;

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
        ];
    }
}
