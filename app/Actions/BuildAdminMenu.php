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

    protected $user;

    /**
     * Complete Action Process
     */
    public function build(User $user)
    {
        $this->user = $user;

        $navBar = [];
        $navBar['Users'] = $this->buildUserMenu();
        $navBar['Equipment'] = $this->buildEquipmentMenu();

        $navBar['App Settings'] = $this->buildSettingsMenu();
        $navBar['App Maintenance'] = $this->buildMaintenanceMenu();

        return $navBar;
    }

    /**
     * Get the administration links for Users
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
            $userBuild[] = [
                'name' => 'User Settings',
                'icon' => 'cog',
                'route' => route('admin.user-settings.get'),
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

    /**
     * Build administration menu for Equipment, Categories and Data Types
     */
    protected function buildEquipmentMenu()
    {
        $nav = [];

        if ($this->checkPermission($this->user, 'Manage Equipment')) {
            $nav = [
                [
                    'name' => 'Equipment Administration',
                    'icon' => 'fas fa-cogs',
                    'route' => route('equipment.index'),
                ],
                [
                    'name' => 'Customer Equipment Data',
                    'icon' => 'fas fa-database',
                    'route' => route('data-types.index'),
                ],
            ];
        }

        return $nav;
    }

    /**
     * Build administration menu for Application Settings
     */
    protected function buildSettingsMenu()
    {
        $nav = [];

        if ($this->checkPermission($this->user, 'App Settings')) {
            $nav = [
                [
                    'name' => 'Application Logo',
                    'icon' => 'fa-image',
                    'route' => route('admin.logo.get'),
                ],
                [
                    'name' => 'Application Configuration',
                    'icon' => 'fa-server',
                    'route' => route('admin.config.get'),
                ],
                [
                    'name' => 'Email Settings',
                    'icon' => 'fas fa-envelope',
                    'route' => route('admin.email.get'),
                ],
                [
                    'name' => 'Security Settings',
                    'icon' => 'fa-lock',
                    'route' => route('admin.security.index'),
                ],
            ];
        }

        return $nav;
    }

    /**
     * Build administration menu for Application Maintenance
     */
    protected function buildMaintenanceMenu()
    {
        $nav = [];

        if ($this->checkPermission($this->user, 'App Settings')) {
            $nav = [
                [
                    'name' => 'Application Logs',
                    'icon' => 'fa-bug',
                    'route' => route('admin.logs.index'),
                ],
                [
                    'name' => 'Log Settings',
                    'icon' => 'fa-sliders',
                    'route' => route('admin.logs.settings.get'),
                ],
                [
                    'name' => 'Backups',
                    'icon' => 'fa-hdd',
                    'route' => route('admin.backups.index'),
                ],
                [
                    'name' => 'Backup Settings',
                    'icon' => 'fa-cog',
                    'route' => route('admin.backups.settings.get'),
                ],
            ];
        }

        return $nav;
    }
}
