<?php

namespace App\Http\Controllers\Customers;

use Inertia\Inertia;
use Illuminate\Database\QueryException;

use App\Models\CustomerFileType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Customers\CustomerFileTypeRequest;

use App\Events\Customers\Admin\CustomerFileTypeCreatedEvent;
use App\Events\Customers\Admin\CustomerFileTypeDeletedEvent;
use App\Events\Customers\Admin\CustomerFileTypeUpdatedEvent;
use App\Events\Customers\Admin\CustomerFileTypeDeletedErrorEvent;

class CustomerFileTypesController extends Controller
{
    /**
     * Show a list of the available customer file types
     */
    public function index()
    {
        $this->authorize('view', CustomerFileType::class);

        return Inertia::render('Customers/FileTypes/Index', [
            'file_types' => CustomerFileType::all()->makeVisible('file_type_id'),
        ]);
    }

    /**
     * Create a new Customer File Type
     */
    public function store(CustomerFileTypeRequest $request)
    {
        $fileType = CustomerFileType::create($request->only('description'));

        event(new CustomerFileTypeCreatedEvent($fileType->makeVisible('file_type_id')));
        return back()->with([
            'message' => 'New File Type Created',
            'type'    => 'success',
        ]);
    }

    /**
     * Update an existing file type's description
     */
    public function update(CustomerFileTypeRequest $request, $id)
    {
        $fileType = CustomerFileType::find($id);
        $fileType->update($request->only(['description']));

        event(new CustomerFileTypeUpdatedEvent($fileType));
        return back()->with([
            'message' => 'File Type Updated Successfully',
            'type'    => 'success',
        ]);
    }

    /**
     * Delete an unused File Type
     */
    public function destroy($id)
    {
        $fileType = CustomerFileType::find($id);
        $this->authorize('view', $fileType);

        try{
            $fileType->delete();
        }
        //  The deletion may fail if the file type is currently in use
        catch(QueryException $e)
        {
            event(new CustomerFileTypeDeletedErrorEvent($fileType, $e));
            return back()->with([
                'message' => 'Unable to delete.  This File Type is in use by some customers.',
                'type'    => 'danger',
            ]);
        }

        event(new CustomerFileTypeDeletedEvent($fileType));
        return back()->with([
            'message' => 'File Type Deleted Successfully',
            'type'    => 'success',
        ]);
    }
}
