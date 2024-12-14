<?php

namespace App\Actions\Misc;

class BuildReportsMenu
{
    /*
    |---------------------------------------------------------------------------
    | Build a list of all available reports
    |---------------------------------------------------------------------------
    */
    public function __invoke(): array
    {
        return [
            'User Reports' => [
                [
                    'name' => 'User Summary Report',
                    'route' => route(
                        'reports.params',
                        ['users', 'user-summary-report']
                    ),
                ],
                [
                    'name' => 'User Login Activity Report',
                    'route' => '#', // route('reports.user.activity'),
                ],
                [
                    'name' => 'User Contributions Report',
                    'route' => '#', // route('reports.user.contribution'),
                ],
                [
                    'name' => 'User Permissions Report',
                    'route' => '#', // route('reports.user.permissions'),
                ],
            ],
            'Customer Reports' => [
                [
                    'name' => 'Customer Summary Report',
                    'route' => '#', // route('reports.customer.summary'),
                ],
                [
                    'name' => 'Customer Files Report',
                    'route' => '#', // route('reports.customer.files'),
                ],
            ],
        ];
    }
}
