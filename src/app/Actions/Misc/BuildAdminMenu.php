<?php

namespace App\Actions\Misc;

use App\Features\TechTipCommentFeature;
use App\Models\User;
use App\Traits\AllowTrait;

class BuildAdminMenu
{
    use AllowTrait;

    /** @var User */
    protected $user;

    /** @var array<string, array> */
    protected $menu;

    /*
    |---------------------------------------------------------------------------
    | Based on the users permissions, build the Administration links that they
    | have permission to view.
    |---------------------------------------------------------------------------
    */
    public function __invoke(User $user): array
    {
        $this->user = $user;

        $this->buildUserMenu();
        $this->buildCustomerMenu();
        $this->buildTechTipMenu();
        $this->buildEquipmentMenu();
        $this->buildFileLinkMenu();
        $this->buildSettingsMenu();
        $this->buildMaintenanceMenu();

        return $this->menu;
    }

    /**
     * Get the administration links for Users
     */
    protected function buildUserMenu(): void
    {
        $userBuild = [];
        if ($this->checkPermission($this->user, 'Manage Users')) {
            $userBuild = [
                [
                    'label' => 'Users',
                    'icon' => 'fas fa-user-edit',
                    'route' => route('admin.user.index'),
                ],
                [
                    'label' => 'Create User',
                    'icon' => 'fas fa-user-plus',
                    'route' => route('admin.user.create'),
                ],
                [
                    'label' => 'List Disabled Users',
                    'icon' => 'fas fa-store-alt-slash',
                    'route' => route('admin.user.deactivated'),
                ],
                [
                    'label' => 'Password Policy',
                    'icon' => 'fas fa-user-lock',
                    'route' => route('admin.user.password-policy.edit'),
                ],
                [
                    'label' => 'User Settings',
                    'icon' => 'cog',
                    'route' => route('admin.user.user-settings.edit'),
                ],
            ];
        }

        if ($this->checkPermission($this->user, 'Manage Permissions')) {
            $userBuild[] = [
                'label' => 'Roles and Permissions',
                'icon' => 'fas fa-users-cog',
                'route' => route('admin.user-roles.index'),
            ];
        }

        $this->menu['Users'] = $userBuild;
    }

    /**
     * Build Administration menu for Customers
     */
    protected function buildCustomerMenu(): void
    {
        $custMenu = [];

        if ($this->checkPermission($this->user, 'Manage Customers')) {
            $custMenu = [
                [
                    'label' => 'Customer Settings',
                    'icon' => 'cog',
                    'route' => route('customers.settings.edit'),
                ],
                [
                    'label' => 'Disabled Customers',
                    'icon' => 'ban',
                    'route' => route('customers.disabled.index'),
                ],
                [
                    'label' => 'Uploaded File Types',
                    'icon' => 'file-import',
                    'route' => route('admin.file-types.index'),
                ],
                [
                    'label' => 'Contact Phone Types',
                    'icon' => 'phone',
                    'route' => route('admin.phone-types.index'),
                ],
                [
                    'label' => 'Re-Assign Customer Site',
                    'icon' => 'truck-moving',
                    'route' => route('customers.re-assign.edit'),
                ],
            ];
        }

        $this->menu['Customers'] = $custMenu;
    }

    /**
     * Build administration menu for Equipment, Categories and Data Types
     */
    protected function buildEquipmentMenu(): void
    {
        $equipMenu = [];

        if ($this->checkPermission($this->user, 'Manage Equipment')) {
            $equipMenu = [
                [
                    'label' => 'Equipment Categories and Types',
                    'icon' => 'fas fa-cogs',
                    'route' => route('equipment.index'),
                ],
                [
                    'label' => 'Customer Equipment Data',
                    'icon' => 'fas fa-database',
                    'route' => route('equipment-data.index'),
                ],
            ];
        }

        if (
            config('customer.enable_workbooks')
            && $this->checkPermission($this->user, 'Manage Equipment Workbooks')
        ) {
            $equipMenu[] = [
                'label' => 'Equipment Workbooks',
                'icon' => 'fa-table',
                'route' => route('workbooks.index'),
            ];
        }

        $this->menu['Equipment'] = $equipMenu;
    }

    /**
     * Build Administration Menu for Tech Tips
     */
    protected function buildTechTipMenu(): void
    {
        $techTipMenu = [];

        if ($this->checkPermission($this->user, 'Manage Tech Tips')) {
            $techTipMenu = [
                [
                    'label' => 'Tech Tip Settings',
                    'icon' => 'cog',
                    'route' => route('admin.tech-tips.settings.edit'),
                ],
                [
                    'label' => 'Tech Tip Types',
                    'icon' => 'file-alt',
                    'route' => route('admin.tech-tips.tip-types.index'),
                ],
                [
                    'label' => 'Disabled Tech Tips',
                    'icon' => 'ban',
                    'route' => route('admin.tech-tips.deleted-tips'),
                ],
            ];

            if ($this->user->features()->active(TechTipCommentFeature::class)) {
                $techTipMenu[] = [
                    'label' => 'View Flagged Comments',
                    'icon' => 'flag',
                    'route' => route('admin.tech-tips.flagged-comments.index'),
                ];
            }
        }

        $this->menu['Tech Tips'] = $techTipMenu;
    }

    /**
     * Build Administration Menu for Application Settings
     */
    protected function buildSettingsMenu(): void
    {
        $settingsMenu = [];

        if ($this->checkPermission($this->user, 'App Settings')) {
            $settingsMenu = [
                [
                    'label' => 'Application Logo',
                    'icon' => 'fa-image',
                    'route' => route('admin.logo.edit'),
                ],
                [
                    'label' => 'Application Configuration',
                    'icon' => 'fa-server',
                    'route' => route('admin.basic-settings.edit'),
                ],
                [
                    'label' => 'Email Settings',
                    'icon' => 'fas fa-envelope',
                    'route' => route('admin.email-settings.edit'),
                ],
                [
                    'label' => 'Security Settings',
                    'icon' => 'fa-lock',
                    'route' => route('admin.security.index'),
                ],
                [
                    'label' => 'Enable/Disable Features',
                    'icon' => 'gears',
                    'route' => route('admin.features.edit'),
                ],
            ];
        }

        $this->menu['Settings'] = $settingsMenu;
    }

    /**
     * Build administration menu for Application Maintenance
     */
    protected function buildMaintenanceMenu(): void
    {
        $maintMenu = [];

        if ($this->checkPermission($this->user, 'App Settings')) {
            $maintMenu = [
                [
                    'label' => 'Application Logs',
                    'icon' => 'fa-bug',
                    'route' => route('maint.logs.index'),
                ],
                [
                    'label' => 'Log Settings',
                    'icon' => 'fa-sliders',
                    'route' => route('maint.logs.settings.show'),
                ],
                [
                    'label' => 'Backups',
                    'icon' => 'fa-hdd',
                    'route' => route('maint.backups.index'),
                ],
                [
                    'label' => 'Backup Settings',
                    'icon' => 'fa-cog',
                    'route' => route('maint.backups.settings.show'),
                ],
            ];
        }

        $this->menu['Maintenance'] = $maintMenu;
    }

    /**
     * Administrative menu for File Links
     */
    protected function buildFileLinkMenu(): void
    {
        $fileLinkMenu = [];

        if (
            config('file-link.feature_enabled')
            && $this->checkPermission($this->user, 'Manage File Links')
        ) {
            $fileLinkMenu = [
                [
                    'label' => 'File Link Settings',
                    'icon' => 'cog',
                    'route' => route('admin.links.settings.edit'),
                ],
                [
                    'label' => 'Manage File Links',
                    'icon' => 'tools',
                    'route' => route('admin.links.manage.index'),
                ],
            ];
        }

        $this->menu['File Links'] = $fileLinkMenu;
    }
}
