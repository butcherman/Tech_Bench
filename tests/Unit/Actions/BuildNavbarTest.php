<?php

namespace Tests\Unit\Actions;

use App\Models\User;
use App\Actions\BuildNavbar;

use Tests\TestCase;

class BuildNavbarTest extends TestCase
{
    public function test_build()
    {
        $user      = User::factory()->create();
        $navbarObj = new BuildNavbar;
        $navbar    = $navbarObj->build($user);

        $this->assertEquals($navbar, [
            [
                'name'  => 'Dashboard',
                'route' => route('dashboard'),
                'icon'  => 'fas fa-tachometer-alt',
            ],
            [
                'name'  => 'Customers',
                'route' => '#', // route('customers.index'),
                'icon'  => 'fas fa-user-tie',
            ],
            [
                'name'  => 'Tech Tips',
                'route' => '#', // route('tech-tips.index'),
                'icon'  => 'fas fa-tools',
            ],
        ]);
    }
}
