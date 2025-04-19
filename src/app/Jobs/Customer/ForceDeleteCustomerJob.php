<?php

namespace App\Jobs\Customer;

use App\Models\Customer;
use App\Models\CustomerFile;
use App\Services\Customer\CustomerAdministrationService;
use App\Services\File\FileUploadService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class ForceDeleteCustomerJob implements ShouldQueue
{
    use Queueable;

    /**
     * @codeCoverageIgnore
     */
    public function __construct(protected Customer $customer) {}

    /**
     * Execute the job.
     */
    public function handle(CustomerAdministrationService $svc, FileUploadService $fileSvc): void
    {
        Log::notice(
            'Force Deleting Customer ' . $this->customer->name . ' and all associated data'
        );

        // Get a list of customer files to be deleted after process is done
        $fileList = CustomerFile::where('cust_id', $this->customer->cust_id)
            ->get()
            ->pluck('file_id');

        $svc->destroyCustomerSites($this->customer);
        $svc->destroyCustomer($this->customer, 'Force Deleting Customer', true);

        $fileSvc->deleteFileByID($fileList);

        Log::notice(
            'Customer ' . $this->customer->name . ' and all associated data has been deleted'
        );
    }
}
