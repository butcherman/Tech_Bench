<?php

namespace App\Actions;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Nwidart\Modules\Facades\Module;

class BuildNavbar
{
    protected $user;

    public function build($user)
    {
        $this->user = $user;

        $admin   = []; // $this->getAdminNavbar();
        $navBar  = $this->getPrimaryNavbar();
        $modules = $this->getModules();

        return array_merge($navBar, $admin, $modules);
    }

    /*
    *   Main navigation menu that is always included
    */
    protected function getPrimaryNavbar()
    {
        return [
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
        ];
    }

    /*
    *   If the user has any admin abilities, show the admin link
    */
    protected function getAdminNavbar()
    {
        if(Gate::allows('admin-link', $this->user))
        {
            return [[
                'name'  => 'Administration',
                'route' => '#', // route('admin.index'),
                'icon'  => 'fas fa-user-shield',
            ]];
        }

        return [];
    }

    /*
    *   If any add-on modules have been installed, add those to the navigation bar
    */
    protected function getModules()
    {
        $nav     = [];
        $modules = Module::allEnabled();

        foreach($modules as $module)
        {
            $name = $module->getLowerName();
            $navData = config($name.'.navbar');

            foreach($navData as $n)
            {
                if($n['enable'])
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
