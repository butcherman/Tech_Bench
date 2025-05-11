<?php

namespace App\Services\Report\Customer;

use App\Http\Resources\Reports\Customers\CustomerSummaryResource;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;

class CustomerSummaryReport extends CustomerReportBase
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        $this->reportParamForm = 'CustomerSummaryReportForm';
        $this->reportParamProps = [];
        // $this->reportDataPage = 'Report/Customer/Summary/Show';
    }

    /**
     * Validate the request to run the report.
     */
    public function validateReportParams(Request $request): Collection
    {
        return Validator::make($request->all(), [
            'all_customers' => ['required', 'boolean'],
            'customer_list' => ['nullable', 'array'],
        ])->safe()->collect();
    }

    /**
     * Run the report
     */
    public function generateReportData(Collection $reportParams): mixed
    {
        // $customerList = $this->getCustomerList($reportParams);

        return $reportParams;

        return [
            'total_customers' => 53, //  $customerList->count(),
            'data' => [], // $customerList, //  CustomerSummaryResource::collection($customerList)
        ];
    }
}
