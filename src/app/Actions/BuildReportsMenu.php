<?php

namespace App\Actions;

class BuildReportsMenu
{
    public static function getMenu()
    {
        return [
            'User Reports' => [
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
                    'route' => '#',
                ],
            ],
            'Customer Reports' => []
        ];
    }
}