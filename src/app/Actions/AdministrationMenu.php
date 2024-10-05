<?php

// @formatted

namespace App\Actions;

use App\Features\TechTipCommentFeature;
use App\Models\User;
use App\Traits\AllowTrait;

class AdministrationMenu
{
    use AllowTrait;

    protected $menu = [];

    protected User $user;

    public function __construct() {}

    /**
     * Create the administration menu based on what the user has permissions to do
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
    protected function buildCustomerMenu(): void
    {
        $custMenu = [];

        if ($this->checkPermission($this->user, 'Manage Customers')) {
            $custMenu = [
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
                    'name' => 'Tech Tip Settings',
                    'icon' => 'cog',
                    'route' => route('admin.tech-tips.settings.edit'),
                ],
                [
                    'name' => 'Tech Tip Types',
                    'icon' => 'file-alt',
                    'route' => route('admin.tech-tips.tip-types.index'),
                ],
                [
                    'name' => 'Disabled Tech Tips',
                    'icon' => 'ban',
                    'route' => route('admin.tech-tips.deleted-tips'),
                ],
            ];

            if ($this->user->features()->active(TechTipCommentFeature::class)) {
                $techTipMenu[] = [
                    'name' => 'View Flagged Comments',
                    'icon' => 'flag',
                    'route' => route('tech-tips.comments.show-flagged'),
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
                [
                    'name' => 'Enable/Disable Features',
                    'icon' => 'gears',
                    'route' => route('admin.features.show'),
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
                    'name' => 'Application Logs',
                    'icon' => 'fa-bug',
                    'route' => route('maint.logs.index'),
                ],
                [
                    'name' => 'Log Settings',
                    'icon' => 'fa-sliders',
                    'route' => route('maint.log-settings.show'),
                ],
                [
                    'name' => 'Backups',
                    'icon' => 'fa-hdd',
                    'route' => route('maint.backup.index'),
                ],
                [
                    'name' => 'Backup Settings',
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
            config('fileLink.feature_enabled')
            && $this->checkPermission($this->user, 'Manage File Links')
        ) {
            $fileLinkMenu = [
                [
                    'name' => 'File Link Settings',
                    'icon' => 'cog',
                    'route' => route('admin.links.settings.show'),
                ],
                [
                    'name' => 'Manage File Links',
                    'icon' => 'tools',
                    'route' => route('admin.links.manage.index'),
                ],
            ];
        }

        $this->menu['File Links'] = $fileLinkMenu;
    }
}
