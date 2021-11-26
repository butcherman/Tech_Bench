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
        $equipMenu    = $this->buildEquipmentMenu();
        $customerMenu = $this->buildCustomerMenu();
        $techTipMenu  = $this->buildTechTipMenu();
        $settingsMenu = $this->buildSettingsMenu();
        $maintMenu    = $this->buildMaintenanceMenu();

        return array_merge($userMenu, $equipMenu, $customerMenu, $techTipMenu, $settingsMenu, $maintMenu);
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
                    'link' => route('admin.user.index'),
                ],
                [
                    'name' => 'Show Deactivated Users',
                    'icon' => 'fas fa-store-alt-slash',
                    'link' => route('admin.deactivated-users'),
                ],
                [
                    'name' => 'Password Policy',
                    'icon' => 'fas fa-user-lock',
                    'link' => route('admin.password-policy'),
                ],
            ];
        }

        $roleBuild = [];
        if($this->checkPermission($this->user, 'Manage Permissions'))
        {
            $roleBuild = [[
                'name' => 'User Roles and Permissions',
                'icon' => 'fas fa-users-cog',
                'link' => route('admin.user-roles.index'),
            ]];
        }

        return ['Users' => array_merge($userBuild, $roleBuild)];
    }

    /**
     * Get the navigation links for Equipment and Equipment Categories
     */
    protected function buildEquipmentMenu()
    {
        $nav = [];

        if($this->checkPermission($this->user, 'Manage Equipment'))
        {
            $nav = [
                'Manage Equipment' => [
                    [
                        'name' => 'Equipment Types & Categories',
                        'icon' => 'fas fa-cogs',
                        'link' => route('equipment.index'),
                    ],
                    [
                        'name' => 'Equipment Data Types (for customers)',
                        'icon' => 'fas fa-database',
                        'link' => route('data-types.index'),
                    ],
                ],
            ];
        }

        return $nav;
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

    /**
     * Build Navigation menu for Tech Tips
     */
    protected function buildTechTipMenu()
    {
        $nav = [];

        if($this->checkPermission($this->user, 'Manage Tech Tips'))
        {
            $nav = [
                'Manage Tech Tips' => [
                    [
                        'name' => 'Tech Tip Types',
                        'icon' => 'fas fa-file-alt',
                        'link' => route('admin.tips.tip-types.index'),
                    ],
                    [
                        'name' => 'View Deleted Tech Tips',
                        'icon' => 'fas fa-ban',
                        'link' => route('admin.tips.deleted'),
                    ],
                    [
                        'name' => 'View Flagged Comments',
                        'icon' => 'far fa-flag',
                        'link' => route('tips.comments.index'),
                    ],
                ],
            ];
        }

        return $nav;
    }

    /**
     * Build navigation menu for Application Settings
     */
    protected function buildSettingsMenu()
    {
        $nav = [];

        if($this->checkPermission($this->user, 'App Settings'))
        {
            $nav = [
                'Manage Application Settings' => [
                    [
                        'name' => 'Application Logo',
                        'icon' => 'fas fa-image',
                        'link' => route('admin.get-logo'),
                    ],
                    [
                        'name' => 'Application Configuration',
                        'icon' => 'fas fa-server',
                        'link' => route('admin.get-config'),
                    ],
                    [
                        'name' => 'Email Settings',
                        'icon' => 'fas fa-envelope',
                        'link' => route('admin.get-email'),
                    ],
                ],
            ];
        }

        return $nav;
    }

    /**
     * Build navigation menu for Application Maintenance
     */
    protected function buildMaintenanceMenu()
    {
        $nav = [];

        if($this->checkPermission($this->user, 'App Settings'))
        {
            $nav = [
                'Application Maintenance' => [
                    [
                        'name' => 'Application Logs',
                        'icon' => 'fas fa-bug',
                        'link' => route('admin.logs.index'),
                    ],
                    //  TODO - Finish me!!!
                    // [
                    //     'name' => 'Application Backups',
                    //     'icon' => '',
                    //     'link' => '#',
                    // ],
                    // [
                    //     'name' => 'Application Updates',
                    //     'icon' => '',
                    //     'link' => '#',
                    // ],
                ],
            ];
        }

        return $nav;
    }
}
