<?php

namespace App\Http\Controllers\Customers;

use App\Events\Customer\CustomerFileCreatedEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\Customers\CustomerFileRequest;
use App\Models\Customer;
use App\Models\CustomerFile;
use App\Traits\FileTrait;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class CustomerFileController extends Controller
{
    use FileTrait;

    /**
     * Store a newly created customer file
     */
    public function store(CustomerFileRequest $request)
    {
        $this->disk = 'customers';
        $this->folder = $request->input('cust_id');

        if ($savedFile = $this->getChunk($request)) {
            $request->checkForShared();
            $request->appendFileData($savedFile->file_id);

            $newFile = CustomerFile::create($request->all());
            Log::channel(['daily', 'cust'])->info('New Customer File created for '.$newFile->Customer->name, $newFile->toArray());
            event(new CustomerFileCreatedEvent($newFile->Customer, $newFile, $request->user()));
        }

        return response()->noContent();
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CustomerFileRequest $request, CustomerFile $file)
    {
        $request->checkForShared();
        $request->appendFileTypeId();
        $file->update($request->only(['cust_id', 'name', 'file_type_id', 'shared']));

        Log::channel(['daily', 'cust'])->info('Customer File for '.$file->Customer->name.' has been updated by '.$request->user()->username, $file->toArray());

        return back()->with('success', __('cust.file.updated'));
    }

    /**
     * Remove the customer file
     */
    public function destroy(CustomerFile $file)
    {
        $this->authorize('delete', $file);

        $file->delete();
        Log::channel(['daily', 'cust'])->notice('Customer file for '.$file->Customer->name.' has been deleted by '.Auth::user()->username, $file->toArray());

        return back()->with('danger', __('cust.file.deleted'));
    }

    /**
     * Restore a soft deleted file
     */
    public function restore(CustomerFile $file)
    {
        $this->authorize('restore', $file);

        $file->restore();
        Log::stack(['daily', 'cust'])->info('Customer File for '.$file->Customer->name.' has been restored by '.Auth::user()->username, $file->toArray());

        return back()->with('success', __('cust.file.restored'));
    }

    /**
     * Force Delete a customer file
     */
    public function forceDelete(CustomerFile $file)
    {
        $this->authorize('forceDelete', $file);

        $file->forceDelete();
        Log::stack(['daily', 'cust'])->notice('Customer File for '.$file->Customer->name.' has been force deleted by '.Auth::user()->username, $file->toArray());

        return back()->with('danger', __('cust.file.force_deleted'));
    }
}
