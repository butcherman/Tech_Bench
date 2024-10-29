<?php

namespace Tests\Unit\Actions\Misc;

use App\Actions\Misc\BuildNavBar;
use App\Models\User;
use Tests\TestCase;

class BuildNavBarUnitTest extends TestCase
{
    public function test_build_default_navbar(): void
    {
        $shouldBe = [
            [
                'name' => 'Dashboard',
                'route' => route('dashboard'),
                'icon' => 'fas fa-tachometer-alt',
            ],
            [
                'name' => 'Customers',
                'route' => '#', // route('customers.index'),
                'icon' => 'fas fa-user-tie',
            ],
            [
                'name' => 'Tech Tips',
                'route' => '#', // route('tech-tips.index'),
                'icon' => 'fas fa-tools',
            ],
        ];

        $user = User::factory()->create();
        $navbar = (new BuildNavBar)->handle($user);

        $this->assertEquals($shouldBe, $navbar);
    }

    public function test_build_installer_navbar(): void
    {
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
                'name' => 'Customers',
                'route' => '#', // route('customers.index'),
                'icon' => 'fas fa-user-tie',
            ],
            [
                'name' => 'Tech Tips',
                'route' => '#', // route('tech-tips.index'),
                'icon' => 'fas fa-tools',
            ],
        ];

        $user = User::factory()->create(['role_id' => 1]);
        $navbar = (new BuildNavBar)->handle($user);

        $this->assertEquals($shouldBe, $navbar);
    }

    public function test_build_administrator_navbar(): void
    {
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
                'name' => 'Customers',
                'route' => '#', // route('customers.index'),
                'icon' => 'fas fa-user-tie',
            ],
            [
                'name' => 'Tech Tips',
                'route' => '#', // route('tech-tips.index'),
                'icon' => 'fas fa-tools',
            ],
        ];

        $user = User::factory()->create(['role_id' => 2]);
        $navbar = (new BuildNavBar)->handle($user);

        $this->assertEquals($shouldBe, $navbar);
    }
}
