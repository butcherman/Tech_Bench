<?php

namespace App\Services\Report\Customer;

use App\Http\Resources\Reports\Customers\CustomerSummaryResource;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;

/**
 * @codeCoverageIgnore
 */
class CustomerSummaryReport extends CustomerReportBase
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        $this->reportParamPage = 'Report/Customer/Summary/Index';
        $this->reportDataPage = 'Report/Customer/Summary/Show';
        $this->reportParamProps = [];
    }

    /**
     * Validate the request to run the report.
     */
    public function validateReportParams(Request $request): Collection
    {
        return Validator::make($request->all(), [
            'disabledCustomers' => ['required', 'boolean'],
        ])->safe()->collect();
    }

    /**
     * Run the report
     */
    public function generateReportData(Collection $reportParams): mixed
    {
        $customerList = $this->getCustomerList($reportParams);

        return [
            'total_customers' => $customerList->count(),
            'data' => CustomerSummaryResource::collection($customerList)
        ];
    }
}
