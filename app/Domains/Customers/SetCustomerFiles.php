<?php

namespace App\Domains\Customers;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

use App\Domains\FilesDomain;

use App\Customers;
use App\CustomerFiles;

use App\Http\Requests\CustomerFileNewRequest;
use App\Http\Requests\CustomerFileUpdateRequest;

use Pion\Laravel\ChunkUpload\Receiver\FileReceiver;
use Pion\Laravel\ChunkUpload\Handler\HandlerFactory;

class SetCustomerFiles extends FilesDomain
{
    protected $custID;

    public function __construct($custID)
    {
        $this->custID = $custID;
        $this->path = config('filesystems.paths.customers').DIRECTORY_SEPARATOR.$custID;
    }

    //  Create a new note for the customer
    public function createFile(CustomerFileNewRequest $request)
    {
        $this->receiver = new FileReceiver('file', $request, HandlerFactory::classFromRequest($request));

        $save = $this->receiver->receive();
        if($save->isFinished())
        {
            $fileID = $this->saveFile($save->getFile());

            //  Input the file into the customer files table
            CustomerFiles::create([
                'file_id'      => $fileID,
                'file_type_id' => $request->file_type_id,
                'cust_id'      => $request->cust_id,
                'user_id'      => Auth::user()->user_id,
                'shared'       => $request->shared ? 1 : 0,
                'name'         => $request->name
            ]);

            return true;
        }

        return false;
    }

    //  Update an existing file for the customer
    public function updateFile(CustomerFileUpdateRequest $request, $fileID)
    {
        if($request->shared)
        {
            $this->checkParent();
        }

        CustomerFiles::find($fileID)->update([
            'name'         => $request->name,
            'file_type_id' => $request->customer_file_types['file_type_id'],
            'cust_id'      => $request->cust_id,
            'shared'       => $request->shared,
        ]);

        Log::info('Customer File updated for Customer ID '.$this->custID.' updated by '.Auth::user()->full_name.'. File Details - ', array($request));
        return true;
    }

    //  Delete an existing file for the customer
    public function deleteCustFile($id)
    {
        $fileData = CustomerFiles::find($id);
        $fileID   = $fileData->file_id;
        Log::notice('A file for a customer has been deleted by '.Auth::user()->full_name.'.  File Data - ', array($fileData));
        $fileData->delete();

        $this->deleteFile($fileID);

        return true;
    }

    //  Verify if the parent should get the note
    protected function checkParent()
    {
        $parent = Customers::find($this->custID)->parent_id;
        if($parent)
        {
            $this->custID = $parent;
        }
    }
}
