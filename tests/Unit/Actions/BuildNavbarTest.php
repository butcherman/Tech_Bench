<?php

namespace Tests\Unit\Actions;

use App\Models\User;
use App\Actions\BuildNavbar;
use Nwidart\Modules\Facades\Module;
use Tests\TestCase;

class BuildNavbarTest extends TestCase
{
    public function test_build()
    {
        $user      = User::factory()->create();
        $navbarObj = new BuildNavbar;
        $navbar    = $navbarObj->build($user);


        $this->assertEquals($navbar, array_merge([
            [
                'name'  => 'Dashboard',
                'route' => route('dashboard'),
                'icon'  => 'fas fa-tachometer-alt',
            ],
            [
                'name'  => 'Customers',
                'route' => route('customers.index'),
                'icon'  => 'fas fa-user-tie',
            ],
            [
                'name'  => 'Tech Tips',
                'route' => route('tech-tips.index'),
                'icon'  => 'fas fa-tools',
            ],
        ], $this->getModuleNav()));
    }

    protected function getModuleNav()
    {
        $nav     = [];
        $modules = Module::allEnabled();

        foreach($modules as $module)
        {
            $name    = $module->getLowerName();
            $navData = config($name.'.navbar');

            if($navData)
            {
                foreach($navData as $n)
                {
                    $nav[] = [
                        'name'  => $n['name'],
                        'route' => route($n['route']),
                        'icon'  => $n['icon'],
                    ];
                }
            }
        }

        return $nav;
    }
}
