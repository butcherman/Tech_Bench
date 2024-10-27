<?php

namespace App\Actions\Misc;

use App\Models\User;

class BuildNavBar
{
    public function handle(User $user): array
    {
        return $this->getPrimaryNav();
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
}
