<?php

namespace App\Actions;

use App\Models\User;
use Illuminate\Support\Facades\Gate;

/**
 * Build the primary navigation bar for the app.
 * This includes links that may be allowed/disallowed or
 * Features that are enables/disabled
 */
class BuildNavbar
{
    // TODO - Unit Test Class
    public static function build(User $user): array
    {
        $primaryNav = [
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
            [
                'name' => 'Tech Tips',
                'route' => route('tech-tips.index'),
                'icon' => 'fas fa-tools',
            ],
        ];

        // If the Reports Nav exists, move it under Dashboard
        if ($reportsNav = self::getReportsNav($user)) {
            array_splice($primaryNav, 1, 0, [$reportsNav]);
        }

        // If the Admin Nav exists, move it just under the Dashboard
        if ($adminNav = self::getAdminNav($user)) {
            array_splice($primaryNav, 1, 0, [$adminNav]);
        }

        return $primaryNav;

    }

    /**
     * If the user has any Administrative Abilities, include Admin Link
     */
    protected static function getAdminNav(User $user): bool|array
    {
        if (Gate::allows('admin-link', $user)) {
            return [
                'name' => 'Administration',
                'route' => route('admin.index'),
                'icon' => 'fas fa-user-shield',
            ];
        }

        return false;
    }

    /**
     * If the user can access the Reports link, include that link
     */
    protected static function getReportsNav(User $user): bool|array
    {
        if (Gate::allows('reports-link', $user)) {
            return [
                'name' => 'Reports',
                'icon' => 'chart-bar',
                'route' => route('reports.index'),
            ];
        }

        return false;
    }
}
