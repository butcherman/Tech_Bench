<?php

namespace Tests\Unit\Actions\User;

use App\Actions\User\BuildUserNavbar;
use App\Models\User;
use Tests\TestCase;

class BuildUserNavbarUnitTest extends TestCase
{
    /*
    |---------------------------------------------------------------------------
    | __invoke()
    |---------------------------------------------------------------------------
    */
    public function test_build_default_navbar(): void
    {
        $shouldBe = [
            'base' => [
                [
                    'label' => 'Dashboard',
                    'route' => route('dashboard'),
                    'icon' => 'house',
                ],
                [
                    'label' => 'Customers',
                    'route' => '#', // route('customers.index'),
                    'icon' => 'people-group',
                ],
                [
                    'label' => 'Tech Tips',
                    'route' => '#', // route('tech-tips.index'),
                    'icon' => 'book-open',
                ],
            ],
            'admin' => [],
        ];

        $user = User::factory()->create();
        $navbar = (new BuildUserNavbar($user))();

        $this->assertEquals($shouldBe, $navbar);
    }

    public function test_build_file_link_navbar(): void
    {
        config(['file-link.feature_enabled' => true]);

        $shouldBe = [
            'base' => [
                [
                    'label' => 'Dashboard',
                    'route' => route('dashboard'),
                    'icon' => 'house',
                ],
                [
                    'label' => 'Customers',
                    'route' => '#', // route('customers.index'),
                    'icon' => 'people-group',
                ],
                [
                    'label' => 'Tech Tips',
                    'route' => '#', // route('tech-tips.index'),
                    'icon' => 'book-open',
                ],
                [
                    'label' => 'File Links',
                    'route' => '#', // route('links.index'),
                    'icon' => 'link',
                ],
            ],
            'admin' => [],
        ];

        $user = User::factory()->create();
        $navbar = (new BuildUserNavbar($user))();

        $this->assertEquals($shouldBe, $navbar);
    }

    public function test_build_installer_navbar(): void
    {
        $shouldBe = [
            'base' => [
                [
                    'label' => 'Dashboard',
                    'route' => route('dashboard'),
                    'icon' => 'house',
                ],
                [
                    'label' => 'Customers',
                    'route' => '#', // route('customers.index'),
                    'icon' => 'people-group',
                ],
                [
                    'label' => 'Tech Tips',
                    'route' => '#', // route('tech-tips.index'),
                    'icon' => 'book-open',
                ],
            ],
            'admin' => [
                [
                    'label' => 'Administration',
                    'route' => route('admin.index'),
                    'icon' => 'cog',
                ],
                [
                    'label' => 'Maintenance',
                    'route' => '#',
                    'icon' => 'wrench',
                ],
                [
                    'label' => 'Reports',
                    'icon' => 'chart-bar',
                    'route' => '#', // route('reports.index'),
                ],
            ],
        ];

        $user = User::factory()->create(['role_id' => 1]);
        $navbar = (new BuildUserNavbar($user))();

        $this->assertEquals($shouldBe, $navbar);
    }

    public function test_build_administrator_navbar(): void
    {
        $shouldBe = [
            'base' => [
                [
                    'label' => 'Dashboard',
                    'route' => route('dashboard'),
                    'icon' => 'house',
                ],
                [
                    'label' => 'Customers',
                    'route' => '#', // route('customers.index'),
                    'icon' => 'people-group',
                ],
                [
                    'label' => 'Tech Tips',
                    'route' => '#', // route('tech-tips.index'),
                    'icon' => 'book-open',
                ],
            ],
            'admin' => [
                [
                    'label' => 'Administration',
                    'route' => route('admin.index'),
                    'icon' => 'cog',
                ],
                [
                    'label' => 'Maintenance',
                    'route' => '#',
                    'icon' => 'wrench',
                ],
                [
                    'label' => 'Reports',
                    'icon' => 'chart-bar',
                    'route' => '#', // route('reports.index'),
                ],
            ],
        ];

        $user = User::factory()->create(['role_id' => 2]);
        $navbar = (new BuildUserNavbar($user))();

        $this->assertEquals($shouldBe, $navbar);
    }

    public function test_build_reports_navbar(): void
    {
        $shouldBe = [
            'base' => [
                [
                    'label' => 'Dashboard',
                    'route' => route('dashboard'),
                    'icon' => 'house',
                ],
                [
                    'label' => 'Customers',
                    'route' => '#', // route('customers.index'),
                    'icon' => 'people-group',
                ],
                [
                    'label' => 'Tech Tips',
                    'route' => '#', // route('tech-tips.index'),
                    'icon' => 'book-open',
                ],
            ],
            'admin' => [
                [
                    'label' => 'Reports',
                    'icon' => 'chart-bar',
                    'route' => '#', // route('reports.index'),
                ],
            ],
        ];

        $user = User::factory()->create(['role_id' => 3]);
        $navbar = (new BuildUserNavbar($user))();

        $this->assertEquals($shouldBe, $navbar);
    }
}
