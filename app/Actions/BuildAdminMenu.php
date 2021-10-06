<?php

namespace App\Actions;

use App\Models\User;
use App\Traits\AllowTrait;

class BuildAdminMenu
{
    use AllowTrait;

    protected $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Get the Admin links that the user has permission to access
     */
    public function execute()
    {

        $userMenu     = $this->buildUserMenu();
        $customerMenu = $this->buildCustomerMenu();

        return array_merge($userMenu, $customerMenu);
    }

    /**
     * Get the navigation links for Users
     */
    protected function buildUserMenu()
    {
        $userBuild = [];
        if($this->checkPermission($this->user, 'Manage Users'))
        {
            $userBuild = [
                [
                    'name' => 'Create New User',
                    'icon' => 'fas fa-user-plus',
                    'link' => route('admin.user.create'),
                ],
                [
                    'name' => 'Modify User',
                    'icon' => 'fas fa-user-edit',
                    'link' => '#', // route('admin.user.list'),
                ],
                [
                    'name' => 'Show Deactivated Users',
                    'icon' => 'fas fa-store-alt-slash',
                    'link' => '#', // route('admin.disabled.index'),
                ],
            ];
        }

        $roleBuild = [];
        if($this->checkPermission($this->user, 'Manage Permissions'))
        {
            $roleBuild = [[
                'name' => 'User Roles and Permissions',
                'icon' => 'fas fa-users-cog',
                'link' => '#', // route('admin.user-roles.index'),
            ]];
        }

        return ['Users' => array_merge($userBuild, $roleBuild)];
    }


    /**
     * Get the navigation links for customers
     */
    protected function buildCustomerMenu()
    {
        $nav = [];

        if($this->checkPermission($this->user, 'Manage Customers'))
        {
            $nav = [
                'Manage Customers' => [
                    [
                        'name' => 'Change Customer ID Number',
                        'icon' => 'fas fa-fingerprint',
                        'link' => route('admin.cust.change-id.index'),
                    ],
                    [
                        'name' => 'View Deactivated Customers',
                        'icon' => 'fas fa-ban',
                        'link' => route('admin.cust.show-deactivated'),
                    ],
                    [
                        'name' => 'Customer Uploaded File Types',
                        'icon' => 'fas fa-file-alt',
                        'link' => route('admin.cust.file-types.index'),
                    ],
                ],
            ];
        }

        return $nav;
    }
}
