<?php

namespace App\Actions;

use App\Models\User;
use App\Traits\AllowTrait;

/**
 * Build Administration Menu based on users permissions.
 * Only show items that they have permission to view
 */
class BuildAdminMenu
{
    // TODO - Unit Test Class
    use AllowTrait;

    protected $menu = [];

    public function __construct(protected User $user)
    {
        $this->buildUserMenu();
        $this->buildCustomerMenu();
        $this->buildEquipmentMenu();
        $this->buildSettingsMenu();
        $this->buildMaintenanceMenu();
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
            $userBuild = [
                [
                    'name' => 'Users',
                    'icon' => 'fas fa-user-edit',
                    'route' => route('admin.user.index'),
                ],
                [
                    'name' => 'Create User',
                    'icon' => 'fas fa-user-plus',
                    'route' => route('admin.user.create'),
                ],
                [
                    'name' => 'List Disabled Users',
                    'icon' => 'fas fa-store-alt-slash',
                    'route' => route('admin.user.deactivated'),
                ],
                [
                    'name' => 'Password Policy',
                    'icon' => 'fas fa-user-lock',
                    'route' => route('admin.user.password-policy.show'),
                ],
                [
                    'name' => 'User Settings',
                    'icon' => 'cog',
                    'route' => route('admin.user.user-settings.show'),
                ],
            ];

        }

        if ($this->checkPermission($this->user, 'Manage Permissions')) {
            $userBuild[] = [
                'name' => 'Roles and Permissions',
                'icon' => 'fas fa-users-cog',
                'route' => route('admin.user-roles.index'),
            ];
        }

        $this->menu['Users'] = $userBuild;
    }

    /**
     * Build Administration menu for Customers
     */
    protected function buildCustomerMenu()
    {
        $nav = [];

        if ($this->checkPermission($this->user, 'Manage Customers')) {
            $nav = [
                [
                    'name' => 'Customer Settings',
                    'icon' => 'cog',
                    'route' => route('customers.settings.edit'),
                ],
                [
                    'name' => 'Disabled Customers',
                    'icon' => 'ban',
                    'route' => route('customers.disabled.index'),
                ],
                [
                    'name' => 'Uploaded File Types',
                    'icon' => 'file-import',
                    'route' => route('admin.file-types.index'),
                ],
                [
                    'name' => 'Contact Phone Types',
                    'icon' => 'phone',
                    'route' => route('admin.phone-types.index'),
                ],
                [
                    'name' => 'Re-Assign Customer Site',
                    'icon' => 'truck-moving',
                    'route' => route('customers.re-assign.edit'),
                ],
            ];
        }

        $this->menu['Customers'] = $nav;
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
                    'name' => 'Equipment Categories and Types',
                    'icon' => 'fas fa-cogs',
                    'route' => route('equipment.index'),
                ],
                [
                    'name' => 'Customer Equipment Data',
                    'icon' => 'fas fa-database',
                    'route' => route('equipment-data.index'),
                ],
            ];
        }

        $this->menu['Equipment'] = $nav;
    }

    /**
     * BUild Administration Menu for Application Settings
     */
    protected function buildSettingsMenu()
    {
        $nav = [];

        if ($this->checkPermission($this->user, 'App Settings')) {
            $nav = [
                [
                    'name' => 'Application Logo',
                    'icon' => 'fa-image',
                    'route' => route('admin.logo.show'),
                ],
                [
                    'name' => 'Application Configuration',
                    'icon' => 'fa-server',
                    'route' => route('admin.basic-settings.show'),
                ],
                [
                    'name' => 'Email Settings',
                    'icon' => 'fas fa-envelope',
                    'route' => route('admin.email-settings.show'),
                ],
                [
                    'name' => 'Security Settings',
                    'icon' => 'fa-lock',
                    'route' => route('admin.security.index'),
                ],
            ];
        }

        $this->menu['Settings'] = $nav;
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
                    'route' => '#', // route('admin.logs.index'),
                ],
                [
                    'name' => 'Log Settings',
                    'icon' => 'fa-sliders',
                    'route' => route('maint.log-settings.show'),
                ],
                [
                    'name' => 'Backups',
                    'icon' => 'fa-hdd',
                    'route' => '#', // route('admin.backups.index'),
                ],
                [
                    'name' => 'Backup Settings',
                    'icon' => 'fa-cog',
                    'route' => '#', // route('admin.backups.settings.get'),
                ],
            ];
        }

        $this->menu['Maintenance'] = $nav;
    }
}
