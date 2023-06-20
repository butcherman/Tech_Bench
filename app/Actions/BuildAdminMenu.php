<?php

namespace App\Actions;

use App\Traits\AllowTrait;

class BuildAdminMenu
{
    use AllowTrait;

    protected $user;

    public function build($user)
    {
        $this->user = $user;

        $navBar = [];
        $navBar['Users'] = $this->buildUserMenu();

        return $navBar;
    }

    /**
     * Get the navigation links for Users
     */
    protected function buildUserMenu()
    {
        $userBuild = [];
        if ($this->checkPermission($this->user, 'Manage Users')) {
            $userBuild[] = [
                'name' => 'Create New User',
                'icon' => 'fas fa-user-plus',
                'route' => route('admin.users.create'),
            ];
            $userBuild[] = [
                'name' => 'Show Users',
                'icon' => 'fas fa-user-edit',
                'route' => route('admin.users.index'),
            ];
            $userBuild[] = [
                'name' => 'Show Deactivated Users',
                'icon' => 'fas fa-store-alt-slash',
                'route' => route('admin.users.deactivated'),
            ];
            $userBuild[] = [
                'name' => 'Password Policy',
                'icon' => 'fas fa-user-lock',
                'route' => route('admin.users.password-policy.get'),
            ];

        }

        if ($this->checkPermission($this->user, 'Manage Permissions')) {
            $userBuild[] = [
                'name' => 'Roles and Permissions',
                'icon' => 'fas fa-users-cog',
                'route' => route('admin.user-roles.index'),
            ];
        }

        return $userBuild;
    }
}
