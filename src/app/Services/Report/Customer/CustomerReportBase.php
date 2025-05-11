<?php

namespace App\Services\Report\Customer;

use App\Contracts\ReportContract;
use App\Models\Customer;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Collection;

abstract class CustomerReportBase implements ReportContract
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
     * Return that this report belongs to the Customer group
     */
    public function getReportGroup(): string
    {
        return 'Customer';
    }

    /**
     * Get the Inertia Form for the Customer Report parameters.
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

    // /**
    //  * Get the Customers that are part of the report
    //  */
    // protected function getCustomerList(Collection $reportParams): EloquentCollection
    // {
    //     return Customer::when($reportParams->get('disabledCustomers'), function ($q) {
    //         return $q->withTrashed();
    //     })->get();
    // }
}
