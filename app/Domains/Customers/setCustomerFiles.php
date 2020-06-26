<?php

namespace App\Domains\Customers;

use App\CustomerFiles;

use App\Domains\Files\SetFiles;

use Illuminate\Support\Facades\Log;

class setCustomerFiles extends SetFiles
{
    protected $custObj;

    public function __construct()
    {
        $this->path    = config('filesystems.paths.default');
        $this->disk    = 'local';
        $this->custObj = new GetCustomerDetails;
    }

    //  Attach a new file to the customer
    public function createFile($request, $userID)
    {
        $this->path = config('filesystems.paths.customers').DIRECTORY_SEPARATOR.$request->cust_id;
        $filename = $this->getChunk($request);
        if(!$filename)
        {
            return false;
        }

        $fileID = $this->addDatabaseRow($filename, $this->path);
        $this->processCustFile($fileID, $userID, $request);

        return true;
    }

    //  Update the database information attached to the file, but will not modify the file itself
    public function updateFile($request, $custFileID)
    {
        $custID = $request->cust_id;
        if($request->shared)
        {
            $parent = $this->custObj->getParentID($request->cust_id);
            if($parent)
            {
                $custID = $parent;
            }
        }

        CustomerFiles::find($custFileID)->update([
            'cust_id'      => $custID,
            'file_type_id' => $request->file_type_id,
            'shared'       => $request->shared,
            'name'         => $request->name,
        ]);

        return true;
    }

    //  Delete a file from the database and storage
    public function deleteCustFile($custFileID)
    {
        $file = CustomerFiles::find($custFileID);
        $fileID = $file->file_id;
        $file->delete();

        $this->deleteFile($fileID);

        return true;
    }

    protected function processCustFile($fileID, $userID, $fileData)
    {
        $custID = $fileData->cust_id;
        if($fileData->shared)
        {
            $parent = $this->custObj->getParentID($fileData->cust_id);
            if($parent)
            {
                $custID = $parent;
            }
        }

        $fileID = CustomerFiles::create([
            'file_id'      => $fileID,
            'file_type_id' => $fileData->file_type_id,
            'cust_id'      => $custID,
            'user_id'      => $userID,
            'shared'       => $fileData->shared === 'true' ? true : false,
            'name'         => $fileData->name,
        ]);

        return $fileID->cust_file_id;
    }
}
