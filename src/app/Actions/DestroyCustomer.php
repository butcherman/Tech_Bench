<?php

namespace App\Actions;

use App\Events\File\FileDataDeletedEvent;
use App\Models\Customer;
use Illuminate\Support\Facades\Log;

/**
 * Complete process of removing a customer and all related data from the
 * database and file system
 */
class DestroyCustomer
{
    protected $isSuccessful = false;

    public function __construct(protected Customer $customer)
    {
        $this->handleJob();
    }

    public function wasSuccessful(): bool
    {
        return $this->isSuccessful;
    }

    protected function handleJob(): void
    {
        $this->destroyFiles();
        $this->destroySites();

        $this->customer->forceDelete();

        $this->isSuccessful = true;
    }

    /**
     * Remove all Customer Files from the disk if not in use by other items
     */
    protected function destroyFiles(): void
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
    protected function destroySites(): void
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
