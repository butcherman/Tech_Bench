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
                    'route' => route(
                        'reports.params',
                        ['users', 'user-login-activity-report']
                    ),
                ],
                [
                    'name' => 'User Contributions Report',
                    'route' => route(
                        'reports.params',
                        ['users', 'user-contributions-report']
                    ),
                ],
                [
                    'name' => 'User Permissions Report',
                    'route' => route(
                        'reports.params',
                        ['users', 'user-permissions-report']
                    ),
                ],
            ],
            'Customer Reports' => [
                [
                    'name' => 'Customer Summary Report',
                    'route' => route(
                        'reports.params',
                        ['customers', 'customer-summary-report']
                    ),
                ],
                [
                    'name' => 'Customer Files Report',
                    'route' => route(
                        'reports.params',
                        ['customers', 'customer-files-report']
                    ),
                ],
            ],
        ];
    }
}