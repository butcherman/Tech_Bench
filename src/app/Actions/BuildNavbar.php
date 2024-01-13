<?php

namespace App\Actions;

use App\Models\User;
use Illuminate\Support\Facades\Gate;

class BuildNavbar
{
    public static function build(User $user)
    {
        $primaryNav = [
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

        $adminNav = self::getAdminNav($user);

        // If the Admin Nav exists, move it just under the Dashboard
        if ($adminNav) {
            array_splice($primaryNav, 1, 0, $adminNav);
        }

        return $primaryNav;

    }

    /**
     * If the user has any Administrative Abilities, include Admin Link
     */
    protected static function getAdminNav(User $user)
    {
        $nav = false;

        if (Gate::allows('admin-link', $user)) {
            $nav[] = [
                'name' => 'Administration',
                'route' => '#', // route('admin.index'),
                'icon' => 'fas fa-user-shield',
            ];
        }

        return $nav;
    }
}
