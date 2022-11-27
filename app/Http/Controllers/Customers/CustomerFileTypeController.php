<?php

namespace App\Http\Controllers\Customers;

use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\QueryException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Customers\CustomerFileTypeRequest;
use App\Models\CustomerFileType;

class CustomerFileTypeController extends Controller
{
    /**
     * Display a listing of the available file types
     */
    public function index()
    {
        $this->authorize('view', CustomerFileType::class);

        return Inertia::render('Customers/FileTypes', [
            'file-types' => CustomerFileType::all()->makeVisible('file_type_id'),
        ]);
    }

    /**
     * Store a newly created customer file type
     */
    public function store(CustomerFileTypeRequest $request)
    {
        $newType = CustomerFileType::create($request->only(['description']));
        Log::stack(['daily', 'cust'])->info('New Customer File Type created by '.$request->user()->username, $newType->toArray());
        return back()->with('success', __('cust.files.type_created'));
    }

    /**
     * Update the specified customer file type
     */
    public function update(CustomerFileTypeRequest $request, CustomerFileType $file_type)
    {
        $file_type->update($request->only(['description']));
        Log::stack(['daily', 'cust'])->info('Customer File Type updated by '.$request->user()->username, $file_type->toArray());
        return back()->with('success', __('cust.files.type_updated'));
    }

    /**
     * Remove the specified customer file type
     */
    public function destroy(CustomerFileType $file_type)
    {
        $this->authorize('delete', $file_type);

        try
        {
            $file_type->delete();
        }
        catch(QueryException $e)
        {
            Log::stack(['daily', 'cust'])->alert('Unable to delete File Type '.$file_type->description.'.  It is still in use');
            return back()->withErrors(['error' => 'This File Type is in use and cannot be deleted at this time']);
        }

        Log::stack(['daily', 'cust'])->info('Customer File Type '.$file_type->description.' deleted by '.Auth::user()->username);
        return back()->with('success', __('cust.files.type_deleted'));
    }
}
