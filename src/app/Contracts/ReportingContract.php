<?php

namespace App\Contracts;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Collection;

interface ReportingContract
{
    /**
     * Inertia page that will render the form requiring the Report Parameters.
     *
     * @return string
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
     *
     * @return string
     */
    public function getReportDataPage(): string;

    /**
     * Validate the Report Parameters Form.
     *
     * @return Collection
     */
    public function validateReportParams(Request $request): Collection;

    /**
     * Generate the requested Report.
     *
     * @return AnonymousResourceCollection
     */
    public function generateReportData(Collection $reportParams): AnonymousResourceCollection;
}
