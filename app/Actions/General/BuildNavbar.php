<?php

namespace App\Actions\General;

use App\Models\UserRolePermissionTypes;
use Illuminate\Support\Facades\Gate;

class BuildNavbar
{
    protected $user;

    public function build($user)
    {
        $this->user = $user;

        $admin  = $this->getAdminNavbar();
        $navBar = $this->getPrimaryNavbar();

        return array_merge($navBar, $admin);
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
            [
                'name'  => 'Tech Tips',
                'route' => '#',
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
                'route' => route('admin.index'),
                'icon'  => 'fas fa-user-shield',
            ]];
        }

        return [];
    }
}
