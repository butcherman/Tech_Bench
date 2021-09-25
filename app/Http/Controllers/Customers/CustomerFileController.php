<?php

namespace App\Http\Controllers\Customers;

use App\Traits\FileTrait;
use Illuminate\Http\Request;

use App\Models\Customer;
use App\Models\CustomerFile;
use App\Models\CustomerFileType;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customers\CustomerFileRequest;

use App\Events\Customers\Files\CustomerFileAddedEvent;
use App\Events\Customers\Files\CustomerFileDeletedEvent;
use App\Events\Customers\Files\CustomerFileForceDeletedEvent;
use App\Events\Customers\Files\CustomerFileRestoredEvent;
use App\Events\Customers\Files\CustomerFileUpdatedEvent;

class CustomerFileController extends Controller
{
    use FileTrait;

    /**
     * Store a file for a specific customer
     */
    public function store(CustomerFileRequest $request)
    {
        //  Make sure that the file information was stored in the users session
        $this->checkForFile();
        $fileData = $request->session()->pull('new-file-upload');

        $cust    = Customer::findOrFail($request->cust_id);
        $cust_id = $cust->cust_id;

        //  If the equipment is shared, it must be assigned to the parent site
        if($request->shared && $cust->parent_id > 0)
        {
            $cust_id = $cust->parent_id;
        }

        $newFile = CustomerFile::create([
            'file_id'      => $fileData[0]->file_id,
            'file_type_id' => CustomerFileType::where('description', $request->type)->first()->file_type_id,
            'cust_id'      => $cust_id,
            'user_id'      => $request->user()->user_id,
            'shared'       => $request->shared,
            'name'         => $request->name,
        ]);

        event(new CustomerFileAddedEvent(Customer::find($request->cust_id), $newFile));
        return back()->with([
            'message' => 'File Added',
            'type'    => 'success',
        ]);
    }

    /**
     * Update the details for a customer uploaded file - not the file itself
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

        $fileData = CustomerFile::find($id);
        $fileData->update([
            'file_type_id' => CustomerFileType::where('description', $request->type)->first()->file_type_id,
            'cust_id'      => $cust_id,
            'shared'       => $request->shared,
            'name'         => $request->name,
        ]);

        event(new CustomerFileUpdatedEvent($cust, $fileData));
        return back()->with([
            'message' => 'File Information Updated',
            'type'    => 'success'
        ]);
    }

    /**
     * Delete a customer file
     */
    public function destroy($id)
    {
        $this->authorize('delete', CustomerFile::class);
        $file = CustomerFile::find($id);
        $file->delete();

        event(new CustomerFileDeletedEvent(Customer::find($file->cust_id), $file));
        return back()->with([
            'message' => 'File Deleted',
            'type'    => 'danger',
        ]);
    }

    /*
    *   Restore a deleted file
    */
    public function restore($id)
    {
        $this->authorize('restore', CustomerFile::class);
        $file = CustomerFile::withTrashed()->where('cust_file_id', $id)->first();
        $file->restore();

        event(new CustomerFileRestoredEvent(Customer::find($file->cust_id), $file));
        return back()->with(['message' => 'Customer File restored', 'type' => 'success']);
    }

    /*
    *   Permanently delete a file
    */
    public function forceDelete($id)
    {
        $this->authorize('forceDelete', CustomerFile::class);

        $file = CustomerFile::withTrashed()->where('cust_file_id', $id)->first();
        $file->forceDelete();
        $this->deleteFile($file->file_id);

        event(new CustomerFileForceDeletedEvent(Customer::find($file->cust_id), $file));
        return back()->with(['message' => 'File permanently deleted', 'type' => 'danger']);
    }
}
