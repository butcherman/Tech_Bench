<?php

namespace App\Service\Reports;

use App\Http\Requests\Report\Customer\CustomerSummaryRequest;
use App\Http\Resources\CustomerSummaryResource;
use App\Models\Customer;

class CustomerSummaryReport extends Reports
{
    public function __construct(protected CustomerSummaryRequest $request)
    {
        parent::__construct();
    }

    protected function buildReportData()
    {
        $customerList = $this->request->disabledCustomers ?
            Customer::withTrashed()->get() : Customer::all();

        $this->reportData = [
            'total_customers' => $customerList->count(),
            'data' => CustomerSummaryResource::collection($customerList),
        ];
    }
}