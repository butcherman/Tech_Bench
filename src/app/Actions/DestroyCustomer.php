<?php

namespace App\Actions;

use App\Events\File\FileDataDeletedEvent;
use App\Models\Customer;
use Illuminate\Support\Facades\Log;

class DestroyCustomer
{
    protected $isSuccessful = false;

    public function __construct(protected Customer $customer)
    {
        $this->handleJob();
    }

    public function wasSuccessful()
    {
        return $this->isSuccessful;
    }

    protected function handleJob()
    {
        $this->destroyFiles();
        $this->destroySites();

        $this->customer->forceDelete();

        $this->isSuccessful = true;
    }

    protected function destroyFiles()
    {
        $fileList = $this->customer->CustomerFile;

        foreach ($fileList as $fileData) {
            $fileData->forceDelete();
            event(new FileDataDeletedEvent($fileData->FileUpload->file_id));


        }
    }

    protected function destroySites()
    {
        // Clear out the primary site id so that all sites can be destroyed
        $this->customer->primary_site_id = null;
        $this->customer->save();

        Log::channel('cust')->debug('Destroying Customer Sites for ' . $this->customer->name);
        $siteList = $this->customer->CustomerSite;

        // Remove all sites associated with this customer
        $siteList->each(function ($site) {
            $site->forceDelete();
            Log::channel('cust')
                ->debug('Customer Site ' . $site->site_name . ' destroyed');
        });
    }
}