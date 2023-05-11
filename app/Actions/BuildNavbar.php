<?php

namespace App\Actions;

use App\Traits\AllowTrait;
use Illuminate\Support\Facades\Gate;

class BuildNavbar
{
    use AllowTrait;

    protected $user;

    public function build($user)
    {
        $this->user = $user;

        $admin = $this->getAdminNavbar();
        $navBar = $this->getPrimaryNavbar();
        array_splice($navBar, 1, 0, $admin); //  Move the Admin link just under the Dashboard link

        return $navBar;
    }

    /*
    *   Main navigation menu that is always included
    */
    protected function getPrimaryNavbar()
    {
        return [
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
        if (Gate::allows('admin-link', $this->user)) {
            $nav[] = [
                'name' => 'Administration',
                'route' => route('admin.index'),
                'icon' => 'fas fa-user-shield',
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
}
