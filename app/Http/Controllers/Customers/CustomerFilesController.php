<?php

namespace App\Http\Controllers\Customers;

use App\Traits\FileTrait;
use App\Models\FileUploads;
use App\Models\CustomerFile;
use App\Models\CustomerFileType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Customers\CustomerFileRequest;

class CustomerFilesController extends Controller
{
    use FileTrait;

    protected $disk;

    public function __construct()
    {
        $this->disk = 'customers';
    }

    /**
     *  Upload a new file
     */
    public function store(CustomerFileRequest $request)
    {
        //  Process the chunk of file being uploaded
        $status = $this->getChunk($request, $this->disk, $request->cust_id);

        //  If the file upload is completed, save to database
        if($status['done'] === 100)
        {
            $newFile = FileUploads::create([
                'disk'      => $this->disk,
                'folder'    => $request->cust_id,
                'file_name' => $status['filename'],
                'public'    => false,
            ]);

            CustomerFile::create([
                'file_id'      => $newFile->file_id,
                'file_type_id' => CustomerFileType::where('description', $request->type)->first()->file_type_id,
                'cust_id'      => $request->cust_id,
                'user_id'      => $request->user()->user_id,
                'shared'       => filter_var($request->shared, FILTER_VALIDATE_BOOL),
                'name'         => $request->name,
            ]);

            return response()->noContent();
        }

        //  If upload is still in progress, send current status of upload
        return response($status);
    }

    /**
     *  Ajax call to get the files for a specific customer
     */
    public function show($id)
    {
        return CustomerFile::where('cust_id', $id)->with('FileUpload')->get();
    }

    /**
     *  Delete a customer File
     */
    public function destroy($id)
    {
        CustomerFile::find($id)->delete();

        return response()->noContent();
    }
}
