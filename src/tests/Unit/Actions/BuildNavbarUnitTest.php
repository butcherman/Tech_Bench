<?php

namespace Tests\Unit\Actions;

use App\Actions\BuildNavbar;
use App\Models\User;
use Tests\TestCase;

class BuildNavbarUnitTest extends TestCase
{
    /**
     * Test Navbar menu with default roles
     */
    public function test_navbar_as_installer()
    {
        /** @var User $user */
        $user = User::factory()->create(['role_id' => 1]);
        $this->actingAs($user);

        $obj = new BuildNavbar;
        $navbar = $obj->getNavbar($user);

        $shouldBe = [
            [
                'name' => 'Dashboard',
                'route' => route('dashboard'),
                'icon' => 'fas fa-tachometer-alt',
            ],
            [
                'name' => 'Administration',
                'route' => route('admin.index'),
                'icon' => 'fas fa-user-shield',
            ],
            [
                'name' => 'Reports',
                'icon' => 'chart-bar',
                'route' => route('reports.index'),
            ],
            [
                'name' => 'Customers',
                'route' => route('customers.index'),
                'icon' => 'fas fa-user-tie',
            ],
            [
                'name' => 'Tech Tips',
                'route' => route('tech-tips.index'),
                'icon' => 'fas fa-tools',
            ],
        ];

        $this->assertEquals($navbar, $shouldBe);
    }

    public function test_navbar_as_installer_with_file_links()
    {
        config(['fileLink.feature_enabled' => true]);

        /** @var User $user */
        $user = User::factory()->create(['role_id' => 1]);
        $this->actingAs($user);

        $obj = new BuildNavbar;
        $navbar = $obj->getNavbar($user);

        $shouldBe = [
            [
                'name' => 'Dashboard',
                'route' => route('dashboard'),
                'icon' => 'fas fa-tachometer-alt',
            ],
            [
                'name' => 'Administration',
                'route' => route('admin.index'),
                'icon' => 'fas fa-user-shield',
            ],
            [
                'name' => 'Reports',
                'icon' => 'chart-bar',
                'route' => route('reports.index'),
            ],
            [
                'name' => 'Customers',
                'route' => route('customers.index'),
                'icon' => 'fas fa-user-tie',
            ],
            [
                'name' => 'Tech Tips',
                'route' => route('tech-tips.index'),
                'icon' => 'fas fa-tools',
            ],
            [
                'name' => 'File Links',
                'icon' => 'link',
                'route' => route('links.index'),
            ],
        ];

        $this->assertEquals($navbar, $shouldBe);
    }

    public function test_navbar_as_admin()
    {
        /** @var User $user */
        $user = User::factory()->create(['role_id' => 2]);
        $this->actingAs($user);

        $obj = new BuildNavbar;
        $navbar = $obj->getNavbar($user);

        $shouldBe = [
            [
                'name' => 'Dashboard',
                'route' => route('dashboard'),
                'icon' => 'fas fa-tachometer-alt',
            ],
            [
                'name' => 'Administration',
                'route' => route('admin.index'),
                'icon' => 'fas fa-user-shield',
            ],
            [
                'name' => 'Reports',
                'icon' => 'chart-bar',
                'route' => route('reports.index'),
            ],
            [
                'name' => 'Customers',
                'route' => route('customers.index'),
                'icon' => 'fas fa-user-tie',
            ],
            [
                'name' => 'Tech Tips',
                'route' => route('tech-tips.index'),
                'icon' => 'fas fa-tools',
            ],
        ];

        $this->assertEquals($navbar, $shouldBe);
    }

    public function test_navbar_as_reports()
    {
        /** @var User $user */
        $user = User::factory()->create(['role_id' => 3]);
        $this->actingAs($user);

        $obj = new BuildNavbar;
        $navbar = $obj->getNavbar($user);

        $shouldBe = [
            [
                'name' => 'Dashboard',
                'route' => route('dashboard'),
                'icon' => 'fas fa-tachometer-alt',
            ],
            [
                'name' => 'Reports',
                'icon' => 'chart-bar',
                'route' => route('reports.index'),
            ],
            [
                'name' => 'Customers',
                'route' => route('customers.index'),
                'icon' => 'fas fa-user-tie',
            ],
            [
                'name' => 'Tech Tips',
                'route' => route('tech-tips.index'),
                'icon' => 'fas fa-tools',
            ],
        ];

        $this->assertEquals($navbar, $shouldBe);
    }

    public function test_navbar_as_tech()
    {
        /** @var User $user */
        $user = User::factory()->create(['role_id' => 4]);
        $this->actingAs($user);

        $obj = new BuildNavbar;
        $navbar = $obj->getNavbar($user);

        $shouldBe = [
            [
                'name' => 'Dashboard',
                'route' => route('dashboard'),
                'icon' => 'fas fa-tachometer-alt',
            ],
            [
                'name' => 'Customers',
                'route' => route('customers.index'),
                'icon' => 'fas fa-user-tie',
            ],
            [
                'name' => 'Tech Tips',
                'route' => route('tech-tips.index'),
                'icon' => 'fas fa-tools',
            ],
        ];

        $this->assertEquals($navbar, $shouldBe);
    }
}
