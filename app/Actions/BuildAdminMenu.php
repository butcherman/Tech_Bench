<?php

namespace App\Actions;

use App\Traits\AllowTrait;
use Illuminate\Support\Facades\Gate;

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
        if($this->checkPermission($this->user, 'Manage Users'))
        {
            $userBuild[] = [
                    'name' => 'Create New User',
                    'icon' => 'fas fa-user-plus',
                    'route' => route('admin.users.create'),
            ];
            // $userBuild[] = [
            //         'name' => 'Modify User',
            //         'icon' => 'fas fa-user-edit',
            //         'route' => '#',
            //     ];
            // $userBuild[] = [
            //         'name' => 'Show Deactivated Users',
            //         'icon' => 'fas fa-store-alt-slash',
            //         'route' => '#',
            // ];
            // $userBuild[] = [
            //         'name' => 'Password Policy',
            //         'icon' => 'fas fa-user-lock',
            //         'route' => '#',
            // ];

        }

        if($this->checkPermission($this->user, 'Manage Permissions'))
        {
            // $userBuild[] = [
            //     'name' => 'User Roles and Permissions',
            //     'icon' => 'fas fa-users-cog',
            //     'route' => '#',
            // ];
        }

        return $userBuild;
    }
}
