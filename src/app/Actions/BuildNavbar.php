<?php

namespace App\Actions;

use App\Features\FileLinkFeature;
use App\Models\User;
use Illuminate\Support\Facades\Gate;

/**
 * Build the primary navigation bar for the app.
 * This includes links that may be allowed/disallowed or
 * Features that are enables/disabled
 */
class BuildNavbar
{
    protected User $user;

    public function getNavbar(User $user): array
    {
        $this->user = $user;
        $nav = $this->getPrimaryNav();

        // If the Reports Nav exists, move it under Dashboard
        if ($reportsNav = $this->getReportsNav()) {
            array_splice($nav, 1, 0, [$reportsNav]);
        }

        // If the Administration Nav exists, move it just under the Dashboard
        if ($adminNav = $this->getAdminNav()) {
            array_splice($nav, 1, 0, [$adminNav]);
        }

        // Add any additional features
        if ($featureNav = $this->getFileLinkNav()) {
            $nav[] = $featureNav;
        }

        return $nav;
    }

    /**
     * Generic Navigation that appears for all registered users
     */
    protected function getPrimaryNav(): array
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
            [
                'name' => 'Tech Tips',
                'route' => route('tech-tips.index'),
                'icon' => 'fas fa-tools',
            ],
        ];
    }

    /**
     * If the user has any Administrative Abilities, include Admin Link
     */
    protected function getAdminNav(): array|bool
    {
        if (! Gate::allows('admin-link', $this->user)) {
            return false;
        }

        return [
            'name' => 'Administration',
            'route' => route('admin.index'),
            'icon' => 'fas fa-user-shield',
        ];
    }

    /**
     * If the user can access the Reports link, include that link
     */
    protected function getReportsNav(): array|bool
    {
        if (! Gate::allows('reports-link', $this->user)) {
            return false;
        }

        return [
            'name' => 'Reports',
            'icon' => 'chart-bar',
            'route' => route('reports.index'),
        ];
    }

    /**
     * If the user can access File Links, include that link
     */
    protected function getFileLinkNav(): array|bool
    {
        if (! $this->user->features()->active(FileLinkFeature::class)) {
            return false;
        }

        return [
            'name' => 'File Links',
            'icon' => 'link',
            'route' => route('links.index'),
        ];
    }
}
