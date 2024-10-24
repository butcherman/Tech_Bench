<?php

namespace App\Service\Customer;

use App\Events\File\FileDataDeletedEvent;
use App\Http\Requests\Customer\CustomerFileRequest;
use App\Models\Customer;
use App\Models\CustomerFile;
use App\Models\FileUpload;
use App\Traits\FileTrait;

class CustomerFileService
{
    use FileTrait;

    public function processIncomingFile(
        CustomerFileRequest $requestData,
        Customer $customer
    ): void {
        $this->setFileData('customers', $customer->cust_id);

        if ($savedFile = $this->getChunk($requestData)) {
            $newFile = $this->createCustomerFile(
                $requestData,
                $customer,
                $savedFile
            );

            // Attach file type relationships
            $this->processAssociations($requestData, $newFile);
        }
    }

    public function createCustomerFile(
        CustomerFileRequest $requestData,
        Customer $customer,
        FileUpload $fileData
    ): CustomerFile {
        $custFile = new CustomerFile($requestData->only([
            'name',
            'file_type_id',
        ]));

        // Attach Relationships
        $custFile->User()->associate($requestData->user());
        $custFile->Customer()->associate($customer);
        $custFile->FileUpload()->associate($fileData);

        $custFile->save();

        return $custFile;
    }

    public function updateCustomerFile(
        CustomerFileRequest $requestData,
        CustomerFile $customerFile
    ): void {
        $customerFile->update($requestData->only(['name', 'file_type_id']));

        $this->processAssociations($requestData, $customerFile);
    }

    public function destroyCustomerFile(
        CustomerFile $customerFile,
        ?bool $force = false
    ): void {
        if ($force) {
            $customerFile->forceDelete();

            event(new FileDataDeletedEvent($customerFile->FileUpload->file_id));

            return;
        }

        $customerFile->delete();
    }

    public function destroyAllCustomerFiles(Customer $customer): void
    {
        CustomerFile::withoutBroadcasting(function () use ($customer) {
            $fileList = $customer->CustomerFile;
            foreach ($fileList as $fileData) {
                $this->destroyCustomerFile($fileData, true);
            }
        });
    }

    public function restoreCustomerFile(CustomerFile $customerFile): void
    {
        $customerFile->restore();
    }

    /**
     * Add the proper associated models and remove all others
     */
    protected function processAssociations(
        CustomerFileRequest $requestData,
        CustomerFile $customerFile
    ): void {
        match ($requestData->file_type) {
            'equipment' => $this->associateEquipment(
                $customerFile,
                $requestData->cust_equip_id
            ),
            'site' => $this->associateSites(
                $customerFile,
                is_array($requestData->site_list)
                    ? $requestData->site_list
                    : json_decode($requestData->site_list)
            ),
            default => $this->removeAssociations($customerFile),
        };
    }

    /**
     * Associate an equipment id with the file, and remove all other associations
     */
    protected function associateEquipment(CustomerFile $customerFile, int $equipId): void
    {
        $customerFile->cust_equip_id = $equipId;
        $customerFile->save();

        $customerFile->CustomerSite()->sync([]);
    }

    /**
     * Associate a list of sites with the file and remove all other associations
     */
    protected function associateSites(CustomerFile $customerFile, array $siteList): void
    {
        $customerFile->CustomerSite()->sync($siteList);

        $customerFile->CustomerEquipment()->dissociate();
    }

    /**
     * Remove all file associations to make it a general file
     */
    protected function removeAssociations(CustomerFile $customerFile): void
    {
        $customerFile->CustomerEquipment()->dissociate();
        $customerFile->CustomerSite()->sync([]);
    }
}
