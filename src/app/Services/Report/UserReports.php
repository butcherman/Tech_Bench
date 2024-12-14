<?php

namespace App\Services\Report;

use App\Contracts\ReportingContract;
use App\Models\User;
use Illuminate\Support\Collection;

class UserReports implements ReportingContract
{
    /**
     * Create a new class instance.
     */
    public function __construct(protected string $reportName) {}

    /*
    |---------------------------------------------------------------------------
    | Report Parameters
    |---------------------------------------------------------------------------
    */

    /**
     * Get the Inertia Page for the User Report parameters.
     */
    public function getReportParamPage(): string
    {
        return match ($this->reportName) {
            'user-summary-report' => 'Report/User/Details/Index',
            default => abort(404),
        };
    }

    /**
     * Get the props that are included with the Inertia page
     */
    public function getReportParamProps(): array
    {
        return match ($this->reportName) {
            'user-summary-report' => ['user-list' => User::all()],
        };
    }

    /*
    |---------------------------------------------------------------------------
    | Report Data
    |---------------------------------------------------------------------------
    */

    public function getReportDataPage()
    {
        //
    }

    public function getReportData(Collection $reportParams)
    {
        dd($reportParams);
    }
}
