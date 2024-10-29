<?php

namespace App\Actions\Misc;

use App\Models\User;
use App\Traits\AllowTrait;

class BuildNavBar
{
    use AllowTrait;

    /** @var User */
    protected $user;

    /**
     * Build the dynamic Navigation Bar for the authenticated user
     */
    public function handle(User $user): array
    {
        $this->user = $user;

        $nav = $this->getPrimaryNav();

        // If the Administration Nav exists, move it just under the Dashboard
        if ($adminNav = $this->getAdminNav()) {
            array_splice($nav, 1, 0, [$adminNav]);
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
                'route' => '#', // route('customers.index'),
                'icon' => 'fas fa-user-tie',
            ],
            [
                'name' => 'Tech Tips',
                'route' => '#', // route('tech-tips.index'),
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
            'name' => 'Administration',
            'route' => route('admin.index'),
            'icon' => 'fas fa-user-shield',
        ];
    }
}
