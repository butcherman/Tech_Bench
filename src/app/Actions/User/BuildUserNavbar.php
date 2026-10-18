<?php

namespace App\Actions\User;

use App\Models\User;
use App\Traits\AllowTrait;
use Illuminate\Support\Collection;

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
        return [
            'base' => $this->getPrimaryNav()->toArray(),
            'admin' => $this->getAdminNav()->toArray(),
        ];
    }

    /**
     * Generic Navigation that appears for all registered users
     */
    private function getPrimaryNav(): Collection
    {
        return collect([
            [
                'label' => 'Dashboard',
                'route' => route('dashboard'),
                'icon' => 'house',
            ],
            [
                'label' => 'Customers',
                'route' => '#', // route('customers.index'),
                'icon' => 'people-group',
            ],
            [
                'label' => 'Tech Tips',
                'route' => '#', // route('tech-tips.index'),
                'icon' => 'book-open',
            ],
        ])->when(config('file-link.feature_enabled'), function ($collection) {
            return $collection->add([
                'label' => 'File Links',
                'icon' => 'link',
                'route' => '#', // route('links.index'),
            ]);
        });
    }

    /**
     * If the user has any Administrative Abilities, include Admin Link
     */
    private function getAdminNav(): Collection
    {
        return collect([])->when($this->seeAdminLink($this->user), function ($collection) {
            $collection->add([
                'label' => 'Administration',
                'route' => '#', // route('admin.index'),
                'icon' => 'cog',
            ]);
        })->when($this->seeReportLink($this->user), function ($collection) {
            $collection->add([
                'label' => 'Reports',
                'icon' => 'chart-bar',
                'route' => '#', // route('reports.index'),
            ]);
        });
    }
}
