<?php

namespace Tests\Unit\Actions\Misc;

use App\Actions\Misc\BuildAdminMenu;
use App\Models\User;
use Tests\TestCase;

class BuildAdminMenuUnitTest extends TestCase
{
    public function test_build_admin_menu_installer(): void
    {
        $user = User::factory()->create(['role_id' => 1]);
        $shouldBe = $this->getBaseMenu();

        $testObj = new BuildAdminMenu;
        $menu = $testObj->handle($user);

        $this->assertEquals($shouldBe, $menu);
    }

    public function test_build_admin_menu_as_admin(): void
    {
        $user = User::factory()->create(['role_id' => 2]);
        $shouldBe = $this->getBaseMenu();
        $shouldBe['Settings'] = [];
        $shouldBe['Maintenance'] = [];

        $testObj = new BuildAdminMenu;
        $menu = $testObj->handle($user);

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
        ];

        $testObj = new BuildAdminMenu;
        $menu = $testObj->handle($user);

        $this->assertEquals($shouldBe, $menu);
    }

    public function test_build_no_user_access(): void
    {
        $this->changeRolePermission(1, 'Manage Users', false);
        $this->changeRolePermission(1, 'Manage Permissions', false);

        $user = User::factory()->create(['role_id' => 1]);
        $shouldBe = $this->getBaseMenu();
        $shouldBe['Users'] = [];

        $testObj = new BuildAdminMenu;
        $menu = $testObj->handle($user);

        $this->assertEquals($shouldBe, $menu);
    }

    public function test_build_no_customer_access(): void
    {
        $this->changeRolePermission(1, 'Manage Customers', false);

        $user = User::factory()->create(['role_id' => 1]);
        $shouldBe = $this->getBaseMenu();
        $shouldBe['Customers'] = [];

        $testObj = new BuildAdminMenu;
        $menu = $testObj->handle($user);

        $this->assertEquals($shouldBe, $menu);
    }

    public function test_build_no_tech_tip_access(): void
    {
        $this->changeRolePermission(1, 'Manage Tech Tips', false);

        $user = User::factory()->create(['role_id' => 1]);
        $shouldBe = $this->getBaseMenu();
        $shouldBe['Tech Tips'] = [];

        $testObj = new BuildAdminMenu;
        $menu = $testObj->handle($user);

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
                    'route' => route('admin.user.password-policy.edit'),
                ],
                [
                    'name' => 'User Settings',
                    'icon' => 'cog',
                    'route' => route('admin.user.user-settings.edit'),
                ],
                [
                    'name' => 'Roles and Permissions',
                    'icon' => 'fas fa-users-cog',
                    'route' => route('admin.user-roles.index'),
                ],
            ],
            'Customers' => [
                [
                    'name' => 'Customer Settings',
                    'icon' => 'cog',
                    'route' => '#', // route('customers.settings.edit'),
                ],
                [
                    'name' => 'Disabled Customers',
                    'icon' => 'ban',
                    'route' => '#', // route('customers.disabled.index'),
                ],
                [
                    'name' => 'Uploaded File Types',
                    'icon' => 'file-import',
                    'route' => '#', // route('admin.file-types.index'),
                ],
                [
                    'name' => 'Contact Phone Types',
                    'icon' => 'phone',
                    'route' => '#', // route('admin.phone-types.index'),
                ],
                [
                    'name' => 'Re-Assign Customer Site',
                    'icon' => 'truck-moving',
                    'route' => '#', // route('customers.re-assign.edit'),
                ],
            ],
            'Equipment' => [
                [
                    'name' => 'Equipment Categories and Types',
                    'icon' => 'fas fa-cogs',
                    'route' => '#', // route('equipment.index'),
                ],
                [
                    'name' => 'Customer Equipment Data',
                    'icon' => 'fas fa-database',
                    'route' => '#', // route('equipment-data.index'),
                ],
            ],
            'Tech Tips' => [
                [
                    'name' => 'Tech Tip Settings',
                    'icon' => 'cog',
                    'route' => '#', // route('admin.tech-tips.settings.edit'),
                ],
                [
                    'name' => 'Tech Tip Types',
                    'icon' => 'file-alt',
                    'route' => '#', // route('admin.tech-tips.tip-types.index'),
                ],
                [
                    'name' => 'Disabled Tech Tips',
                    'icon' => 'ban',
                    'route' => '#', // route('admin.tech-tips.deleted-tips'),
                ],
                // [
                //     'name' => 'View Flagged Comments',
                //     'icon' => 'flag',
                //     'route' => '#', // route('tech-tips.comments.show-flagged'),
                // ],
            ],
            'Settings' => [
                [
                    'name' => 'Application Logo',
                    'icon' => 'fa-image',
                    'route' => route('admin.logo.edit'),
                ],
                [
                    'name' => 'Application Configuration',
                    'icon' => 'fa-server',
                    'route' => route('admin.basic-settings.edit'),
                ],
                [
                    'name' => 'Email Settings',
                    'icon' => 'fas fa-envelope',
                    'route' => route('admin.email-settings.edit'),
                ],
                [
                    'name' => 'Security Settings',
                    'icon' => 'fa-lock',
                    'route' => route('admin.security.index'),
                ],
                [
                    'name' => 'Enable/Disable Features',
                    'icon' => 'gears',
                    'route' => route('admin.features.edit'),
                ],
            ],
            'Maintenance' => [
                [
                    'name' => 'Application Logs',
                    'icon' => 'fa-bug',
                    'route' => '#', // route('maint.logs.index'),
                ],
                [
                    'name' => 'Log Settings',
                    'icon' => 'fa-sliders',
                    'route' => '#', // route('maint.log-settings.show'),
                ],
                [
                    'name' => 'Backups',
                    'icon' => 'fa-hdd',
                    'route' => '#', // route('maint.backup.index'),
                ],
                [
                    'name' => 'Backup Settings',
                    'icon' => 'fa-cog',
                    'route' => '#', // route('maint.backups.settings.show'),
                ],
            ],
            // 'File Links' => [
            //     [
            //         'name' => 'File Link Settings',
            //         'icon' => 'cog',
            //         'route' => '#', // route('admin.links.settings.show'),
            //     ],
            //     [
            //         'name' => 'Manage File Links',
            //         'icon' => 'tools',
            //         'route' => '#', // route('admin.links.manage.index'),
            //     ],
            // ],
        ];
    }
}
