<?php

namespace Tests\Unit\Actions;

use App\Actions\AdministrationMenu;
use App\Models\User;
use Tests\TestCase;

class AdminMenuTest extends TestCase
{
    /**
     * Test Administration menu with default roles
     */
    public function test_admin_menu_as_installer()
    {
        $user = User::factory()->create(['role_id' => 1]);
        $obj = new AdministrationMenu;
        $menu = $obj($user);

        $shouldBe = [
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
                    'route' => route('admin.user.password-policy.show'),
                ],
                [
                    'name' => 'User Settings',
                    'icon' => 'cog',
                    'route' => route('admin.user.user-settings.show'),
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
            ],
            'Tech Tips' => [
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
                [
                    'name' => 'View Flagged Comments',
                    'icon' => 'flag',
                    'route' => route('tech-tips.comments.show-flagged'),
                ],
            ],
            'Equipment' => [
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
            ],
            'File Links' => [],
            'Settings' => [
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
            ],
            'Maintenance' => [
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
            ],
        ];

        $this->assertEquals($menu, $shouldBe);
    }

    public function test_admin_menu_as_installer_file_link_enabled()
    {
        config(['fileLink.feature_enabled' => true]);
        $user = User::factory()->create(['role_id' => 1]);
        $obj = new AdministrationMenu;
        $menu = $obj($user);

        $shouldBe = [
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
                    'route' => route('admin.user.password-policy.show'),
                ],
                [
                    'name' => 'User Settings',
                    'icon' => 'cog',
                    'route' => route('admin.user.user-settings.show'),
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
            ],
            'Tech Tips' => [
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
                [
                    'name' => 'View Flagged Comments',
                    'icon' => 'flag',
                    'route' => route('tech-tips.comments.show-flagged'),
                ],
            ],
            'Equipment' => [
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
            ],
            'File Links' => [
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
            ],
            'Settings' => [
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
            ],
            'Maintenance' => [
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
            ],
        ];

        $this->assertEquals($menu, $shouldBe);
    }

    public function test_admin_menu_as_admin()
    {
        $user = User::factory()->create(['role_id' => 2]);
        $obj = new AdministrationMenu;
        $menu = $obj($user);

        $shouldBe = [
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
                    'route' => route('admin.user.password-policy.show'),
                ],
                [
                    'name' => 'User Settings',
                    'icon' => 'cog',
                    'route' => route('admin.user.user-settings.show'),
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
            ],
            'Tech Tips' => [
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
                [
                    'name' => 'View Flagged Comments',
                    'icon' => 'flag',
                    'route' => route('tech-tips.comments.show-flagged'),
                ],
            ],
            'Equipment' => [
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
            ],
            'File Links' => [],
            'Settings' => [],
            'Maintenance' => [],
        ];

        $this->assertEquals($menu, $shouldBe);
    }

    public function test_admin_menu_as_reports()
    {
        $user = User::factory()->create(['role_id' => 3]);
        $obj = new AdministrationMenu;
        $menu = $obj($user);

        $shouldBe = [
            'Users' => [],
            'Customers' => [],
            'Tech Tips' => [],
            'Equipment' => [],
            'File Links' => [],
            'Settings' => [],
            'Maintenance' => [],
        ];

        $this->assertEquals($menu, $shouldBe);
    }

    public function test_admin_menu_as_tech()
    {
        $user = User::factory()->create(['role_id' => 4]);
        $obj = new AdministrationMenu;
        $menu = $obj($user);

        $shouldBe = [
            'Users' => [],
            'Customers' => [],
            'Tech Tips' => [],
            'Equipment' => [],
            'File Links' => [],
            'Settings' => [],
            'Maintenance' => [],
        ];

        $this->assertEquals($menu, $shouldBe);
    }
}
