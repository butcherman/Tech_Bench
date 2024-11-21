<?php

namespace App\Services\Customer;

use App\Models\Customer;
use App\Models\CustomerFile;
use App\Models\FileUpload;
use App\Models\User;
use Illuminate\Support\Collection;

class CustomerFileService
{
    /*
    |---------------------------------------------------------------------------
    | Create a new Customer File
    |---------------------------------------------------------------------------
    */
    public function createCustomerFile(
        Collection $requestData,
        FileUpload $fileData,
        Customer $customer,
        User $user
    ): CustomerFile {
        $custFile = new CustomerFile;
        $custFile->name = $requestData->get('name');
        $custFile->file_type_id = $requestData->get('file_type_id');

        // Attach Relationships
        $custFile->FileUpload()->associate($fileData);
        $custFile->Customer()->associate($customer);
        $custFile->User()->associate($user);

        $custFile->save();

        // Attach other necessary Relationships
        $this->processAssociations($requestData, $custFile);

        return $custFile;
    }

    /*
    |---------------------------------------------------------------------------
    | Update an existing Customer File.
    |---------------------------------------------------------------------------
    */
    public function updateCustomerFile(
        Collection $requestData,
        CustomerFile $custFile
    ): CustomerFile {
        $custFile->update($requestData->only(['name', 'file_type_id'])
            ->toArray());

        $this->processAssociations($requestData, $custFile);

        return $custFile->fresh();
    }

    /*
    |---------------------------------------------------------------------------
    | Soft Delete or Force Delete a Customer File.
    |---------------------------------------------------------------------------
    */
    public function destroyCustomerFile(
        CustomerFile $customerFile,
        ?bool $force = false
    ): void {
        if ($force) {
            $customerFile->forceDelete();

            return;
        }

        $customerFile->delete();
    }

    /*
    |---------------------------------------------------------------------------
    | Restore a Soft Deleted Customer File
    |---------------------------------------------------------------------------
    */
    public function restoreCustomerFile(CustomerFile $customerFile): void
    {
        $customerFile->restore();
    }

    /*
    |---------------------------------------------------------------------------
    | Add the proper associated models and remove all others
    |---------------------------------------------------------------------------
    */
    protected function processAssociations(
        Collection $requestData,
        CustomerFile $customerFile
    ): void {
        match ($requestData->get('file_type')) {
            'equipment' => $this->associateEquipment(
                $customerFile,
                $requestData->get('cust_equip_id')
            ),
            'site' => $this->associateSites(
                $customerFile,
                $requestData->get('site_list')
            ),
            default => $this->removeAssociations($customerFile),
        };
    }

    /*
    |---------------------------------------------------------------------------
    | Associate an equipment id with the file, and remove all other associations
    |---------------------------------------------------------------------------
    */
    protected function associateEquipment(
        CustomerFile $customerFile,
        int $equipId
    ): void {
        $customerFile->cust_equip_id = $equipId;
        $customerFile->save();

        $customerFile->CustomerSite()->sync([]);
    }

    /*
    |---------------------------------------------------------------------------
    | Associate a list of sites with the file and remove all other associations
    |---------------------------------------------------------------------------
    */
    protected function associateSites(
        CustomerFile $customerFile,
        array $siteList
    ): void {
        $customerFile->CustomerSite()->sync($siteList);

        $customerFile->CustomerEquipment()->dissociate();
    }

    /*
    |---------------------------------------------------------------------------
    | Remove all file associations to make it a general file
    |---------------------------------------------------------------------------
    */
    protected function removeAssociations(CustomerFile $customerFile): void
    {
        $customerFile->CustomerEquipment()->dissociate();
        $customerFile->CustomerSite()->sync([]);
    }
}
