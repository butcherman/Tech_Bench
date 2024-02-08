<?php

namespace App\Actions;

use App\Models\User;
use App\Traits\AllowTrait;

/**
 * Build Administration Menu based on users permissions.
 * Only show items that they have permission to adjust
 */
class BuildAdminMenu
{
    use AllowTrait;

    protected $menu = [];

    public function __construct(protected User $user)
    {
        $this->buildUserMenu();
    }

    /**
     * Return the completed menu
     */
    public function getAdminMenu()
    {
        return $this->menu;
    }

    /**
     * Get the administration links for Users
     */
    protected function buildUserMenu()
    {
        $userBuild = [];
        if ($this->checkPermission($this->user, 'Manage Users')) {
            $userBuild[] = [
                'name' => 'Users',
                'icon' => 'fas fa-user-edit',
                'route' => route('admin.user.index'),
            ];
            $userBuild[] = [
                'name' => 'Create User',
                'icon' => 'fas fa-user-plus',
                'route' => route('admin.user.create'),
            ];
            $userBuild[] = [
                'name' => 'List Disabled Users',
                'icon' => 'fas fa-store-alt-slash',
                'route' => route('admin.user.deactivated'),
            ];
            $userBuild[] = [
                'name' => 'Password Policy',
                'icon' => 'fas fa-user-lock',
                'route' => route('admin.user.password-policy.show'),
            ];
            $userBuild[] = [
                'name' => 'User Settings',
                'icon' => 'cog',
                'route' => route('admin.user.user-settings.show'),
            ];

        }

        if ($this->checkPermission($this->user, 'Manage Permissions')) {
            $userBuild[] = [
                'name' => 'Roles and Permissions',
                'icon' => 'fas fa-users-cog',
                'route' => '#', // route('admin.user-roles.index'),
            ];
        }

        $this->menu['Users'] = $userBuild;
    }
}
