<?php

namespace App\Actions\Misc;

class BuildReportsMenu
{
    /**
     * Return all available reports that can be run.
     */
    public function __invoke(): array
    {
        return [
            'User Reports' => $this->userReports(),
            'Customer Reports' => $this->customerReports(),
        ];
    }

    protected function userReports(): array
    {
        return [
            [
                'name' => 'User Summary Report',
                'url' => route('reports.params', [
                    'users',
                    'user-summary-report'
                ]),
            ],
            [
                'name' => 'User Login Activity Report',
                'url' => route('reports.params', [
                    'users',
                    'user-login-activity-report'
                ]),
            ],
            [
                'name' => 'User Contributions Report',
                'url' => route('reports.params', [
                    'users',
                    'user-contributions-report'
                ]),
            ],
            [
                'name' => 'User Permissions Report',
                'url' => route('reports.params', [
                    'users',
                    'user-permissions-report'
                ]),
            ],
        ];
    }

    protected function customerReports(): array
    {
        return [
            [
                'name' => 'Customer Summary Report',
                'url' => route('reports.params', [
                    'customers',
                    'customer-summary-report'
                ]),
            ],
            [
                'name' => 'Customer Files Report',
                'url' => route('reports.params', [
                    'customers',
                    'customer-files-report'
                ]),
            ],
            [
                'name' => 'Customer Equipment Report',
                'url' => route('reports.params', [
                    'customers',
                    'customer-equipment-report'
                ]),
            ],
        ];
    }
}
