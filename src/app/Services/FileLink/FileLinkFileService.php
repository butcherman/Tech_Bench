<?php

namespace App\Services\FileLink;

use App\Events\File\FileDataDeletedEvent;
use App\Models\Customer;
use App\Models\FileLink;
use App\Models\FileLinkTimeline;
use App\Models\FileUpload;
use App\Models\User;
use App\Services\Customer\CustomerFileService;
use App\Services\File\FileUploadService;
use Illuminate\Support\Collection;

class FileLinkFileService extends FileUploadService
{
    /**
     * Attach a new file to a File Link
     */
    public function createFileLinkFile(FileLink $link, FileUpload $file, int|string $addedBy): void
    {
        $timeline = new FileLinkTimeline([
            'added_by' => $addedBy,
        ]);

        $link->Timeline()->save($timeline);
        $link->Files()->attach($file, [
            'timeline_id' => $timeline->timeline_id,
            'upload' => is_int($addedBy) ? false : true,
        ]);
    }

    /**
     * Attach file to customer profile and move to proper folder.
     */
    public function moveFileLinkFile(
        Collection $requestData,
        FileLink $link,
        FileUpload $file,
        User $user
    ): void {
        $customer = Customer::find($requestData->get('cust_id'));

        // Assign the customer to the link to make future moves faster
        $notProperCustomer = is_null($link->cust_id) || $link->cust_id !== $customer->cust_id;
        if ($notProperCustomer) {
            $link->cust_id = $customer->cust_id;
            $link->save();
        }

        $fileObj = new CustomerFileService;
        $fileObj->createCustomerFile($requestData, $file, $customer, $user);

        $this->moveUploadedFile($file, $customer->cust_id, 'customers');

        $link->Files()->updateExistingPivot($file->file_id, ['moved' => true]);
    }

    /**
     * Delete a file attached to a file link
     */
    public function destroyFileLinkFile(FileLink $link, FileUpload $file): void
    {
        $link->Files()->detach($file);

        FileDataDeletedEvent::dispatch($file->file_id);
    }

    /**
     * Move any files that are in the tmp folder to the proper folder for link.
     */
    public function checkLinkFileFolder(FileLink $link): void
    {
        $fileList = $link->Files;

        foreach ($fileList as $file) {
            if ($file->disk === 'fileLinks' && $file->folder === 'tmp') {
                $this->moveUploadedFile($file, $link->link_id);
            }
        }
    }

    /**
     * Determine if the files should be listed as public or private
     */
    public function checkLinkFilePermission(FileLink $link): void
    {
        $fileList = $link->Files;

        foreach ($fileList as $file) {
            if (! $file->pivot->upload) {
                $file->public = true;
                $file->save();
            }
        }
    }
}
