<?php

namespace App\Jobs\Customer;

use App\Actions\Customer\ReAssignCustomerSite;
use App\Models\Customer;
use App\Models\CustomerSite;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class ReAssignSiteJob implements ShouldQueue
{
    use Queueable;

    /**
     * @codeCoverageIgnore
     */
    public function __construct(protected int $fromSite, protected int $toCustomer) {}

    /**
     * Execute the job.
     */
    public function handle(ReAssignCustomerSite $svc): void
    {
        Log::notice('Starting Move Customer Site Job', [
            'moving_site_id' => $this->fromSite,
            'to_cust_id' => $this->toCustomer,
        ]);

        $movingSite = CustomerSite::find($this->fromSite);
        $destination = Customer::find($this->toCustomer);

        $svc($movingSite, $destination);

        Log::notice('Move Customer Site Job Completed');
    }
}
