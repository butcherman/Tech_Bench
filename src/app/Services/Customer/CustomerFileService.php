<?php

namespace App\Services\Customer;

use App\Enums\DiskEnum;
use App\Models\Customer;
use App\Models\CustomerFile;
use App\Models\FileUpload;
use App\Models\User;
use App\Services\_Base\HandleFileUploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class CustomerFileService extends HandleFileUploadService
{
    /*
    |---------------------------------------------------------------------------
    | Save the file chunk.  If upload is completed, save file and create
    | a new Customer File Entry.
    |---------------------------------------------------------------------------
    */
    public function processFileRequest(Request $request, Customer $customer)
    {
        $this->setFileData(DiskEnum::customers, $customer->cust_id);
        $savedFile = $this->getChunk($request->file('file'), $request);

        // If file is done, create the DB Entry
        if ($savedFile instanceof \App\Models\FileUpload) {
            $this->createCustomerFile(
                $request->safe()->collect(),
                $savedFile,
                $customer,
                $request->user()
            );
        }
    }

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
        $custFile->name = json_decode($requestData->get('name'));
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
