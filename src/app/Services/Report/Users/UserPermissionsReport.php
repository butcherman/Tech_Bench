<?php

namespace App\Services\Report\Users;

use Illuminate\Support\Collection;

class UserPermissionsReport extends UserReportBase
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        $this->reportParamForm = 'UserPermissionsReportForm';
        $this->reportDataPage = 'UserPermissionsReport';
        $this->reportParamProps = [];
    }

    /**
     * Validate the request to run the report.
     */
    public function getValidationParams(): array
    {
        return [];
    }

    /**
     * Run the report
     */
    public function generateReportData(Collection $reportParams): array
    {
        $data = [];

        return $data;
    }
}
