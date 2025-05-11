<?php

namespace App\Contracts;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;

interface ReportContract
{
    /**
     * Return the group that this form belongs to.
     */
    public function getReportGroup(): string;

    /**
     * Inertia form that will gather Report Parameters.
     */
    public function getReportParamForm(): string;

    /**
     * Props that are included with the Inertia page.
     */
    public function getReportParamProps(): array;

    /**
     * Blade Template that will render the Report Data.
     */
    public function getReportDataPage(): string;

    /**
     * Validate the Report Parameters Form.
     */
    public function validateReportParams(Request $request): Collection;

    /**
     * Generate the requested Report.
     */
    public function generateReportData(Collection $reportParams): mixed;
}
