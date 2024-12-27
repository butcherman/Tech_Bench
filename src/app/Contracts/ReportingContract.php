<?php

namespace App\Contracts;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;

interface ReportingContract
{
    /**
     * Inertia page that will render the form requiring the Report Parameters.
     */
    public function getReportParamPage(): string;

    /**
     * Props that are included with the Inertia Page.
     *
     * @return array<string, string>
     */
    public function getReportParamProps(): array;

    /**
     * Inertia page that will render the Report Data.
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
