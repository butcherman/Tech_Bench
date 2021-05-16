<?php

namespace App\Http\Controllers\Customers;

use App\Traits\FileTrait;

use App\Models\Customer;
use App\Models\FileUploads;
use App\Models\CustomerFile;
use App\Models\CustomerFileType;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customers\CustomerFileRequest;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

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
        $cust    = Customer::findOrFail($request->cust_id);
        $cust_id = $cust->cust_id;

        //  If the equipment is shared, it must be assigned to the parent site
        if(filter_var($request->shared, FILTER_VALIDATE_BOOL) && $cust->parent_id > 0)
        {
            $cust_id = $cust->parent_id;
        }

        //  Process the chunk of file being uploaded
        $status = $this->getChunk($request, $this->disk, $cust_id);

        //  If the file upload is completed, save to database
        if($status['done'] === 100)
        {
            $newFile = FileUploads::create([
                'disk'      => $this->disk,
                'folder'    => $cust_id,
                'file_name' => $status['filename'],
                'public'    => false,
            ]);

            CustomerFile::create([
                'file_id'      => $newFile->file_id,
                'file_type_id' => CustomerFileType::where('description', $request->type)->first()->file_type_id,
                'cust_id'      => $cust_id,
                'user_id'      => $request->user()->user_id,
                'shared'       => filter_var($request->shared, FILTER_VALIDATE_BOOL),
                'name'         => $request->name,
            ]);

            Log::channel('cust')->info('New file '.$request->name.' has been uploaded for Customer '.$cust->name.' by '.$request->user()->username);
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
        $cust = Customer::findOrFail($id);

        return CustomerFile::where('cust_id', $id)
                ->when($cust->parent_id, function($q) use ($cust)
                {
                    $q->orWhere('cust_id', $cust->parent_id)->where('shared', true);
                })
                ->with('FileUpload')
                ->get();
    }

    /*
    *   Update the basic information for an uploaded file
    */
    public function update(CustomerFileRequest $request, $id)
    {
        $cust    = Customer::findOrFail($request->cust_id);
        $cust_id = $cust->cust_id;

        //  If the equipment is shared, it must be assigned to the parent site
        if($request->shared && $cust->parent_id > 0)
        {
            $cust_id = $cust->parent_id;
        }

        CustomerFile::find($id)->update([
            'file_type_id' => CustomerFileType::where('description', $request->type)->first()->file_type_id,
            'cust_id'      => $cust_id,
            'shared'       => $request->shared,
            'name'         => $request->name,
        ]);

        Log::channel('cust')->info('Customer File ID '.$id.' has been updated by '.Auth::user()->username);
        return redirect()->back()->with(['message' => 'File Information Updated', 'type' => 'success']);
    }

    /**
     *  Delete a customer File
     */
    public function destroy($id)
    {
        $this->authorize('delete', CustomerFile::class);

        CustomerFile::find($id)->delete();
        Log::channel('cust')->notice('Customer FIle ID '.$id.' has been deleted by '.Auth::user()->username);
        return response()->noContent();
    }

    /*
    *   Restore a file that was deleted
    */
    public function restore($id)
    {
        $this->authorize('restore', CustomerFile::class);
        $file = CustomerFile::withTrashed()->where('cust_file_id', $id)->first();
        $file->restore();

        Log::channel('cust')->info('Customer File ID '.$id.' has been restored for Customer ID '.$file->cust_id.' by '.Auth::user()->username);

        return redirect()->back()->with(['message' => 'Customer File restored', 'type' => 'success']);
    }

    /*
    *   Permanently delete a file
    */
    public function forceDelete($id)
    {
        $this->authorize('forceDelete', CustomerFile::class);

        $file   = CustomerFile::withTrashed()->where('cust_file_id', $id)->first();
        $fileID = $file->file_id;

        Log::channel('cust')->alert('Customer File ID '.$id.' has been permanently deleted by '.Auth::user()->username);
        $file->forceDelete();
        $this->deleteFile($fileID);

        return redirect()->back()->with(['message' => 'File permanently deleted', 'type' => 'danger']);
    }
}
