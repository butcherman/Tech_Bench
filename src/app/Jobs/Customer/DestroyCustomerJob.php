<?php

namespace App\Jobs\Customer;

use App\Models\Customer;
use App\Service\Customer\CustomerFileService;
use App\Service\Customer\CustomerService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class DestroyCustomerJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(protected Customer $customer) {}

    /**
     * Execute the job.
     */
    public function handle(CustomerFileService $fileSvc, CustomerService $custSvc): void
    {
        Log::info('Destroying Customer - '.$this->customer->name);

        $fileSvc->destroyAllCustomerFiles($this->customer);
        $custSvc->destroyAllSites($this->customer);
        $custSvc->destroyCustomer($this->customer, 'Force Deleting Customer', true);

        Log::info('Customer '.$this->customer->name.' has been destroyed');
    }
}
