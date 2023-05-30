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
        $userMenu = $this->buildUserMenu();
        $equipMenu = $this->buildEquipmentMenu();
        $customerMenu = $this->buildCustomerMenu();
        $techTipMenu = []; // $this->buildTechTipMenu();
        $settingsMenu = $this->buildSettingsMenu();
        $maintenanceMenu = $this->buildMaintenanceMenu();

        return array_merge($userMenu, $equipMenu, $customerMenu, $techTipMenu, $settingsMenu, $maintenanceMenu);
    }

    /**
     * Get the navigation links for Users
     */
    protected function buildUserMenu()
    {
        $userBuild = [];
        if ($this->checkPermission($this->user, 'Manage Users')) {
            $userBuild = [
                [
                    'name' => 'Create New User',
                    'icon' => 'fa-user-plus',
                    'link' => route('admin.users.create'),
                ],
                [
                    'name' => 'Modify User',
                    'icon' => 'fa-user-edit',
                    'link' => route('admin.users.index'),
                ],
                [
                    'name' => 'Show Disabled Users',
                    'icon' => 'fa-store-alt-slash',
                    'link' => route('admin.users.disabled'),
                ],
                [
                    'name' => 'Password Policy',
                    'icon' => 'fas fa-user-lock',
                    'link' => route('admin.users.password-policy.index'),
                ],
            ];
        }

        $roleBuild = [];
        if ($this->checkPermission($this->user, 'Manage Permissions')) {
            $roleBuild = [[
                'name' => 'User Roles and Permissions',
                'icon' => 'fa-users-cog',
                'link' => route('admin.users.roles.index'),
            ]];
        }

        if (count($userBuild) > 0 || count($roleBuild) > 0) {
            return ['Users' => array_merge($userBuild, $roleBuild)];
        }

        // @codeCoverageIgnoreStart
        return [];
        // @codeCoverageIgnoreEnd
    }

    /**
     * Get the navigation links for Equipment and Equipment Categories
     */
    protected function buildEquipmentMenu()
    {
        $nav = [];

        if ($this->checkPermission($this->user, 'Manage Equipment')) {
            $nav = [
                'Manage Equipment' => [
                    [
                        'name' => 'Equipment Categories & Types',
                        'icon' => 'fas fa-cogs',
                        'link' => route('equipment.index'),
                    ],
                    [
                        'name' => 'Equipment Data Types (for customers)',
                        'icon' => 'fas fa-database',
                        'link' => route('data_types.index'),
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

        if ($this->checkPermission($this->user, 'Manage Customers')) {
            $nav = [
                'Manage Customers' => [
                    [
                        'name' => 'Customer Settings',
                        'icon' => 'fa-gears',
                        'link' => route('admin.cust.settings'),
                    ],
                    [
                        'name' => 'Change Customer ID Number',
                        'icon' => 'fas fa-fingerprint',
                        'link' => route('admin.cust.change_id.index'),
                    ],
                    [
                        'name' => 'View Deactivated Customers',
                        'icon' => 'fas fa-ban',
                        'link' => route('admin.cust.deactivated'),
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

        // if($this->checkPermission($this->user, 'Manage Tech Tips'))
        // {
        //     $nav = [
        //         'Manage Tech Tips' => [
        //             [
        //                 'name' => 'Tech Tip Types',
        //                 'icon' => 'fas fa-file-alt',
        //                 'link' => route('admin.tips.tip-types.index'),
        //             ],
        //             [
        //                 'name' => 'View Deleted Tech Tips',
        //                 'icon' => 'fas fa-ban',
        //                 'link' => route('admin.tips.deleted'),
        //             ],
        //             [
        //                 'name' => 'View Flagged Comments',
        //                 'icon' => 'fa-flag',
        //                 'link' => route('tips.comments.index'),
        //             ],
        //         ],
        //     ];
        // }

        return $nav;
    }

    /**
     * Build navigation menu for Application Settings
     */
    protected function buildSettingsMenu()
    {
        $nav = [];

        if ($this->checkPermission($this->user, 'App Settings')) {
            $nav = [
                'Manage Application Settings' => [
                    [
                        'name' => 'Application Logo',
                        'icon' => 'fa-image',
                        'link' => route('admin.get-logo'),
                    ],
                    [
                        'name' => 'Application Configuration',
                        'icon' => 'fa-server',
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

        if ($this->checkPermission($this->user, 'App Settings')) {
            $nav = [
                'Application Maintenance' => [
                    [
                        'name' => 'Application Logs',
                        'icon' => 'fa-bug',
                        'link' => route('admin.logs.index'),
                    ],
                    [
                        'name' => 'Log Settings',
                        'icon' => 'fa-sliders',
                        'link' => route('admin.logs.settings'),
                    ],
                    // [
                    //     'name' => 'Backups',
                    //     'icon' => 'fa-hdd',
                    //     'link' => route('admin.backups.show'),
                    // ],
                    // [
                    //     'name' => 'Backup Settings',
                    //     'icon' => 'fa-hdd',
                    //     'link' => route('admin.backups.index'),
                    // ],
                ],
            ];
        }

        return $nav;
    }
}
