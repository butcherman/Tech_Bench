<?php

namespace App\Actions\General;

use App\Models\UserRolePermissionTypes;

class BuildNavbar
{
    protected $user;

    public function build($user)
    {
        $this->user = $user;

        // $admin  = $this->getAdminNavbar();
        $navBar = $this->getPrimaryNavbar();

        // return array_merge($navBar, $admin);
        return $navBar;
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
                'route' => '#',
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
        $response = UserRolePermissionTypes::whereIsAdminLink(1)->whereHas('UserRolePermissions', function($q)
        {
            $q->where('role_id', $this->user->role_id)->whereAllow(1);
        })->get();

        if($response->count() > 0 ? true : false)
        {
            return [[
                'name'  => 'Administration',
                'route' => '#',
                'icon'  => 'fas fa-user-shield',
            ]];
        }

        return [];
    }
}
