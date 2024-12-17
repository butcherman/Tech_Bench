<?php

namespace App\Services\Report\User;

use App\Contracts\ReportingContract;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Collection;

/**
 * @codeCoverageIgnore
 */
abstract class UserReportBase implements ReportingContract
{
    /**
     * Inertia Page for the User Report Parameters
     *
     * @var string
     */
    protected $reportParamPage;

    /**
     * Props to be included with the Inertia page.
     *
     * @var array<string, mixed>
     */
    protected $reportParamProps;

    /**
     * Inertia Page for the User Report Data.
     *
     * @var string
     */
    protected $reportDataPage;

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
        return $this->reportParamPage;
    }

    /**
     * Get the props that are included with the Inertia page
     */
    public function getReportParamProps(): array
    {
        return $this->reportParamProps;
    }

    /*
    |---------------------------------------------------------------------------
    | Report Data
    |---------------------------------------------------------------------------
    */

    /**
     * Get the Inertia Page for the User Report Data
     */
    public function getReportDataPage(): string
    {
        return $this->reportDataPage;
    }

    /**
     * Get the users that are part of the report.
     */
    protected function getUserList(Collection $reportParams): EloquentCollection
    {
        if ($reportParams->get('allUsers')) {
            return User::when($reportParams->get('disabledUsers'), function ($q) {
                return $q->withTrashed();
            })->get();
        }

        return User::whereIn('username', $reportParams->get('user_list'))->get();
    }
}
