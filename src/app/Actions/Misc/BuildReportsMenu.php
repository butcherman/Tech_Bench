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
            'Customer Reports' => $this->customerReports(),
        ];
    }

    protected function customerReports(): array
    {
        return [
            [
                'name' => 'Customer Summary Report',
                'url' => '#', // route('reports.customer.summary'),
            ],
            [
                'name' => 'Customer Files Report',
                'url' => '#', // route('reports.customer.files'),
            ],
            [
                'name' => 'Customer Equipment Report',
                'url' => '#',
            ]
        ];
    }
}
