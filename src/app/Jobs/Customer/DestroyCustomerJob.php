<?php

namespace App\Jobs\Customer;

use App\Models\Customer;
use App\Services\Customer\CustomerFileService;
use App\Services\Customer\CustomerService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class DestroyCustomerJob implements ShouldQueue
{
    use Queueable;

    /*
    |---------------------------------------------------------------------------
    | Remove all data attached to a customer, and then destroy the customer.
    |---------------------------------------------------------------------------
    */
    public function __construct(protected Customer $customer) {}

    /**
     * Execute the job.
     */
    public function handle(CustomerFileService $fileSvc, CustomerService $custSvc): void
    {
        Log::info('Destroying Customer - '.$this->customer->name);

        // $fileSvc->destroyAllCustomerFiles($this->customer); TODO - Create This Method
        $custSvc->destroyAllSites($this->customer);
        $custSvc->destroyCustomer($this->customer, 'Force Deleting Customer', true);

        Log::info('Customer '.$this->customer->name.' has been destroyed');
    }
}
