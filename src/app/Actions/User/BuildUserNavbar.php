<?php

namespace App\Actions\User;

use App\Features\FileLinkFeature;
use App\Models\User;
use App\Traits\AllowTrait;

/*
|---------------------------------------------------------------------------
| Create the users navbar based on their permission levels.
|---------------------------------------------------------------------------
*/

class BuildUserNavbar
{
    use AllowTrait;

    /**
     * Create a new class instance.
     */
    public function __construct(protected User $user) {}

    public function __invoke(): array
    {
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
                'label' => 'Dashboard',
                'route' => route('dashboard'),
                'icon' => 'fas fa-tachometer-alt',
            ],
            [
                'label' => 'Customers',
                'route' => route('customers.index'),
                'icon' => 'fas fa-user-tie',
            ],
            [
                'label' => 'Tech Tips',
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
        if (! $this->seeAdminLink($this->user)) {
            return false;
        }

        return [
            'label' => 'Administration',
            'route' => route('admin.index'),
            'icon' => 'fas fa-user-shield',
        ];
    }

    /**
     * If the user can access the Reports link, include that link
     */
    protected function getReportsNav(): array|bool
    {
        if (! $this->seeReportLink($this->user)) {
            return false;
        }

        return [
            'label' => 'Reports',
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
            'label' => 'File Links',
            'icon' => 'link',
            'route' => route('links.index'),
        ];
    }
}
