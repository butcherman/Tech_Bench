<?php

namespace App\Actions\General;



class BuildNavbar
{
    protected $user;

    public function build($user)
    {
        $this->user = $user;



        $navBar = $this->getPrimaryNavbar();

        return $navBar;
    }

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
}
