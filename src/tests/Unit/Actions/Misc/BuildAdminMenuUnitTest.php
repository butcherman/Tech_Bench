<?php

namespace Tests\Unit\Actions\Misc;

use App\Actions\Misc\BuildAdminMenu;
use App\Models\User;
use Tests\TestCase;

class BuildAdminMenuUnitTest extends TestCase
{
    /*
    |---------------------------------------------------------------------------
    | build()
    |---------------------------------------------------------------------------
    */
    public function test_build_admin_menu_installer(): void
    {
        $user = User::factory()->create(['role_id' => 1]);
        $shouldBe = $this->getBaseMenu();
        $shouldBe['File Links'] = [];

        $testObj = new BuildAdminMenu;
        $menu = $testObj($user);

        $this->assertEquals($shouldBe, $menu);
    }

    public function test_build_admin_menu_installer_file_links_enabled(): void
    {
        config(['file-link.feature_enabled' => true]);
        config(['customer.enable_workbooks' => true]);

        $user = User::factory()->create(['role_id' => 1]);
        $shouldBe = $this->getBaseMenu();
        $shouldBe['Equipment'][] = [
            'label' => 'Equipment Workbooks',
            'icon' => 'fa-table',
            'route' => route('workbooks.index'),
        ];

        $testObj = new BuildAdminMenu;
        $menu = $testObj($user);

        $this->assertEquals($shouldBe, $menu);
    }

    public function test_build_admin_menu_as_admin(): void
    {
        $user = User::factory()->create(['role_id' => 2]);
        $shouldBe = $this->getBaseMenu();
        $shouldBe['Settings'] = [];
        $shouldBe['Maintenance'] = [];
        $shouldBe['File Links'] = [];

        $testObj = new BuildAdminMenu;
        $menu = $testObj($user);

        $this->assertEquals($shouldBe, $menu);
    }

    public function test_build_admin_menu_no_access(): void
    {
        $user = User::factory()->create();
        $shouldBe = [
            'Users' => [],
            'Customers' => [],
            'Tech Tips' => [],
            'Equipment' => [],
            'Settings' => [],
            'Maintenance' => [],
            'File Links' => [],
        ];

        $testObj = new BuildAdminMenu;
        $menu = $testObj($user);

        $this->assertEquals($shouldBe, $menu);
    }

    public function test_build_no_user_access(): void
    {
        $this->changeRolePermission(1, 'Manage Users', false);
        $this->changeRolePermission(1, 'Manage Permissions', false);

        $user = User::factory()->create(['role_id' => 1]);
        $shouldBe = $this->getBaseMenu();
        $shouldBe['Users'] = [];
        $shouldBe['File Links'] = [];

        $testObj = new BuildAdminMenu;
        $menu = $testObj($user);

        $this->assertEquals($shouldBe, $menu);
    }

    public function test_build_no_customer_access(): void
    {
        $this->changeRolePermission(1, 'Manage Customers', false);

        $user = User::factory()->create(['role_id' => 1]);
        $shouldBe = $this->getBaseMenu();
        $shouldBe['Customers'] = [];
        $shouldBe['File Links'] = [];

        $testObj = new BuildAdminMenu;
        $menu = $testObj($user);

        $this->assertEquals($shouldBe, $menu);
    }

    public function test_build_no_tech_tip_access(): void
    {
        $this->changeRolePermission(1, 'Manage Tech Tips', false);

        $user = User::factory()->create(['role_id' => 1]);
        $shouldBe = $this->getBaseMenu();
        $shouldBe['Tech Tips'] = [];
        $shouldBe['File Links'] = [];

        $testObj = new BuildAdminMenu;
        $menu = $testObj($user);

        $this->assertEquals($shouldBe, $menu);
    }

    /**
     * Base Administration menu
     */
    protected function getBaseMenu(): array
    {
        return [
            'Users' => [
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
                [
                    'label' => 'Roles and Permissions',
                    'icon' => 'fas fa-users-cog',
                    'route' => route('admin.user-roles.index'),
                ],
            ],
            'Customers' => [
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
            ],
            'Equipment' => [
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
            ],
            'Tech Tips' => [
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
                [
                    'label' => 'View Flagged Comments',
                    'icon' => 'flag',
                    'route' => route('admin.tech-tips.flagged-comments.index'),
                ],
            ],
            'Settings' => [
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
            ],
            'Maintenance' => [
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
            ],
            'File Links' => [
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
            ],
        ];
    }
}
