<?php

namespace App\Services\Report\Customer;

use App\Http\Resources\Reports\Customers\CustomerSummaryResource;
use App\Models\Customer;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Arr;

class CustomerSummaryReport extends CustomerReportBase
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        $this->reportParamForm = 'CustomerSummaryReportForm';
        $this->reportParamProps = [];
        $this->reportDataPage = 'CustomerSummaryReport';
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
    public function generateReportData(Collection $reportParams): array
    {
        $custList = $this->getCustomerList(
            $reportParams->get('all_customers'),
            $reportParams->get('customer_list')
        );

        $data = [];

        foreach ($custList as $customer) {
            $data[] = [
                'name' => $customer->name,
                'cust_id' => $customer->cust_id,
                'sites' => $customer->site_count,
                'equipment' => $customer->Equipment->count(),
                'notes' => $customer->Notes->count(),
                'contacts' => $customer->Contacts->count(),
                'files' => $customer->Files->count(),
            ];
        }

        return $data;
    }
}
