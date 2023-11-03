<?php

namespace Tests\Unit\Actions;

use App\Actions\BuildAdminMenu;
use App\Models\User;
use Tests\TestCase;

/**
 * Testing ran on default user Roles
 */
class BuildAdminMenuUnitTest extends TestCase
{
    /**
     * Build Function
     */
    public function test_build_as_installer()
    {
        $user = User::factory()->create(['role_id' => 1]);

        $navBar = (new BuildAdminMenu)->build($user);
        $shouldBe = [
            "Users" => [
                0 => [
                    "name" => "Create New User",
                    "icon" => "fas fa-user-plus",
                    "route" => "http://localhost/administration/users/create",
                ],
                1 => [
                    "name" => "Show Users",
                    "icon" => "fas fa-user-edit",
                    "route" => "http://localhost/administration/users",
                ],
                2 => [
                    "name" => "Show Deactivated Users",
                    "icon" => "fas fa-store-alt-slash",
                    "route" => "http://localhost/administration/user/deactivated-users",
                ],
                3 => [
                    "name" => "Password Policy",
                    "icon" => "fas fa-user-lock",
                    "route" => "http://localhost/administration/user/password-policy",
                ],
                4 => [
                    "name" => "User Settings",
                    "icon" => "cog",
                    "route" => "http://localhost/administration/user-settings",
                ],
                5 => [
                    "name" => "Roles and Permissions",
                    "icon" => "fas fa-users-cog",
                    "route" => "http://localhost/administration/user-roles",
                ],
            ],
            "Equipment" => [
                0 => [
                    "name" => "Equipment Administration",
                    "icon" => "fas fa-cogs",
                    "route" => "http://localhost/equipment",
                ],
                1 => [
                    "name" => "Customer Equipment Data",
                    "icon" => "fas fa-database",
                    "route" => "http://localhost/data-types",
                ],
            ],
            "App Settings" => [
                0 => [
                    "name" => "Application Logo",
                    "icon" => "fa-image",
                    "route" => "http://localhost/administration/logo",
                ],
                1 => [
                    "name" => "Application Configuration",
                    "icon" => "fa-server",
                    "route" => "http://localhost/administration/config",
                ],
                2 => [
                    "name" => "Email Settings",
                    "icon" => "fas fa-envelope",
                    "route" => "http://localhost/administration/email-settings",
                ],
                3 => [
                    "name" => "Security Settings",
                    "icon" => "fa-lock",
                    "route" => "http://localhost/administration/security",
                ],
            ],
            "App Maintenance" => [
                0 => [
                    "name" => "Application Logs",
                    "icon" => "fa-bug",
                    "route" => "http://localhost/administration/logs",
                ],
                1 => [
                    "name" => "Log Settings",
                    "icon" => "fa-sliders",
                    "route" => "http://localhost/administration/logs/settings",
                ],
                2 => [
                    "name" => "Backups",
                    "icon" => "fa-hdd",
                    "route" => "http://localhost/administration/backups",
                ],
                3 => [
                    "name" => "Backup Settings",
                    "icon" => "fa-cog",
                    "route" => "http://localhost/administration/backups/settings",
                ],
            ],
        ];

        $this->assertEquals($navBar, $shouldBe);
    }

    public function test_build_as_administrator()
    {
        $user = User::factory()->create(['role_id' => 2]);

        $navBar = (new BuildAdminMenu)->build($user);
        $shouldBe = [
            "Users" => [
                0 => [
                    "name" => "Create New User",
                    "icon" => "fas fa-user-plus",
                    "route" => "http://localhost/administration/users/create",
                ],
                1 => [
                    "name" => "Show Users",
                    "icon" => "fas fa-user-edit",
                    "route" => "http://localhost/administration/users",
                ],
                2 => [
                    "name" => "Show Deactivated Users",
                    "icon" => "fas fa-store-alt-slash",
                    "route" => "http://localhost/administration/user/deactivated-users",
                ],
                3 => [
                    "name" => "Password Policy",
                    "icon" => "fas fa-user-lock",
                    "route" => "http://localhost/administration/user/password-policy",
                ],
                4 => [
                    "name" => "User Settings",
                    "icon" => "cog",
                    "route" => "http://localhost/administration/user-settings",
                ],
                5 => [
                    "name" => "Roles and Permissions",
                    "icon" => "fas fa-users-cog",
                    "route" => "http://localhost/administration/user-roles",
                ],
            ],
            "Equipment" => [
                0 => [
                    "name" => "Equipment Administration",
                    "icon" => "fas fa-cogs",
                    "route" => "http://localhost/equipment",
                ],
                1 => [
                    "name" => "Customer Equipment Data",
                    "icon" => "fas fa-database",
                    "route" => "http://localhost/data-types",
                ],
            ],
            "App Settings" => [],
            "App Maintenance" => [],
        ];

        $this->assertEquals($navBar, $shouldBe);
    }

    public function test_build_as_reports()
    {
        $user = User::factory()->create(['role_id' => 3]);

        $navBar = (new BuildAdminMenu)->build($user);
        $shouldBe = [
            "Users" => [],
            "Equipment" => [],
            "App Settings" => [],
            "App Maintenance" => [],
        ];

        $this->assertEquals($navBar, $shouldBe);
    }

    public function test_build_as_tech()
    {
        $user = User::factory()->create(['role_id' => 4]);

        $navBar = (new BuildAdminMenu)->build($user);
        $shouldBe = [
            "Users" => [],
            "Equipment" => [],
            "App Settings" => [],
            "App Maintenance" => [],
        ];

        $this->assertEquals($navBar, $shouldBe);
    }
}
