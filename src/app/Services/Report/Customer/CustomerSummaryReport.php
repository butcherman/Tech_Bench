<?php

namespace App\Services\Report\Customer;

use Illuminate\Support\Collection;

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
    public function getValidationParams(): array
    {
        return [
            'all_customers' => ['required', 'boolean'],
            'customer_list' => ['nullable', 'array'],
        ];
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
