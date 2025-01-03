<?php

namespace App\Actions;

class ReportsMenu
{
    /**
     * These are the available reports that can be run
     */
    public function get(): array
    {
        return [
            'User Reports' => [
                [
                    'name' => 'User Details Report',
                    'route' => route('reports.user.details'),
                ],
                [
                    'name' => 'User Login Activity Report',
                    'route' => route('reports.user.activity'),
                ],
                [
                    'name' => 'User Contributions Report',
                    'route' => route('reports.user.contribution'),
                ],
                [
                    'name' => 'User Permissions Report',
                    'route' => route('reports.user.permissions'),
                ],
            ],
            'Customer Reports' => [
                [
                    'name' => 'Customer Summary Report',
                    'route' => route('reports.customer.summary'),
                ],
                [
                    'name' => 'Customer Files Report',
                    'route' => route('reports.customer.files'),
                ],
            ],
        ];
    }
}
