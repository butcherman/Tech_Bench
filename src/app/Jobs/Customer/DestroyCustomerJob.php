<?php

namespace App\Jobs\Customer;

use App\Events\File\FileDataDeletedEvent;
use App\Models\Customer;
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
    public function handle(): void
    {
        Log::info('Destroying Customer - '.$this->customer->name);

        $this->destroyCustomerFiles();
        $this->destroyCustomerSites();

        Log::info('Customer '.$this->customer->name.' has been destroyed');
    }

    /**
     * Remove all Customer Files from the disk if not in use by other items
     */
    protected function destroyCustomerFiles(): void
    {
        $fileList = $this->customer->CustomerFile;

        foreach ($fileList as $fileData) {
            $fileData->forceDelete();
            event(new FileDataDeletedEvent($fileData->FileUpload->file_id));
        }
    }

    /**
     * Remove all related sites from the customer
     */
    protected function destroyCustomerSites(): void
    {
        // Clear out the primary site id so that all sites can be destroyed
        $this->customer->primary_site_id = null;
        $this->customer->save();

        Log::debug('Destroying Customer Sites for '.$this->customer->name);
        $siteList = $this->customer->CustomerSite;

        // Remove all sites associated with this customer
        $siteList->each(function ($site) {
            $site->forceDelete();
            Log::debug('Customer Site '.$site->site_name.' destroyed');
        });
    }
}
