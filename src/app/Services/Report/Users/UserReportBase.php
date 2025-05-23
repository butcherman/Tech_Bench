<?php

namespace App\Services\Report\Users;

use App\Contracts\ReportContract;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Collection;

abstract class UserReportBase implements ReportContract
{
    /**
     * Inertia Page for the User Report Parameters
     *
     * @var string
     */
    protected $reportParamForm;

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
     * Return that this report belongs to the Users group
     */
    public function getReportGroup(): string
    {
        return 'Users';
    }

    /**
     * Get the Inertia Form for the User Report parameters.
     */
    public function getReportParamForm(): string
    {
        return $this->reportParamForm;
    }

    /**
     * Get the props that are included with the Inertia form
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

    protected function getUserList(Collection $reportParams): EloquentCollection
    {
        if ($reportParams->get('all_users')) {
            return User::when($reportParams->get('disabled_users'), function ($q) {
                return $q->withTrashed();
            })->get();
        }

        return User::whereIn('username', $reportParams->get('user_list'))->get();
    }
}
