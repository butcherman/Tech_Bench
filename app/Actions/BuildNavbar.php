<?php

namespace App\Actions;

use Nwidart\Modules\Facades\Module;
use Illuminate\Support\Facades\Gate;

use App\Traits\AllowTrait;

class BuildNavbar
{
    use AllowTrait;

    protected $user;

    public function build($user)
    {
        $this->user = $user;

        $admin   = $this->getAdminNavbar();
        $navBar  = $this->getPrimaryNavbar();
        $modules = $this->getModules();
        array_splice($navBar, 1, 0, $admin); //  Move the Admin link just under the Dashboard link

        return array_merge($navBar, $modules);
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
                'route' => route('customers.index'),
                'icon'  => 'fas fa-user-tie',
            ],
            // [
            //     'name'  => 'Tech Tips',
            //     'route' => route('tech-tips.index'),
            //     'icon'  => 'fas fa-tools',
            // ],
        ];
    }

    /*
    *   If the user has any admin abilities, show the admin link
    */
    protected function getAdminNavbar()
    {
        $nav = [];

        //  Should Admin link show?
        if(Gate::allows('admin-link', $this->user))
        {
            $nav[] = [
                'name'  => 'Administration',
                'route' => route('admin.index'),
                'icon'  => 'fas fa-user-shield',
            ];
        }

        //  Should Reports link show
        // if(Gate::allows('reports-link', $this->user))
        // {
        //     $nav[] = [
        //         'name'  => 'Reports',
        //         'route' => route('reports.index'),
        //         'icon'  => 'fas fa-chart-bar',
        //     ];
        // }

        return $nav;
    }

    /**
    * If any add-on modules have been installed, add those to the navigation bar
    * @codeCoverageIgnore
    */
    protected function getModules()
    {
        $nav     = [];
        // $modules = Module::allEnabled();

        // foreach($modules as $module)
        // {
        //     $name    = $module->getLowerName();
        //     $navData = config($name.'.navbar');

        //     if($navData)
        //     {
        //         foreach($navData as $n)
        //         {
        //             if(!isset($n['perm_name']) || $this->checkPermission($this->user, $n['perm_name']))
        //             {
        //                 $nav[] = [
        //                     'name'  => $n['name'],
        //                     'route' => route($n['route']),
        //                     'icon'  => $n['icon'],
        //                 ];
        //             }
        //         }
        //     }
        // }

        return $nav;
    }
}
