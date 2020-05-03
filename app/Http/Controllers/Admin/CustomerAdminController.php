<?php

namespace App\Http\Controllers\Admin;

use App\Customers;
use App\CustomerFileTypes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Resources\CustomerFileTypesCollection;

class CustomerAdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function($request, $next) {
            $this->authorize('hasAccess', 'Manage Customers');
            return $next($request);
        });
    }

    //  Form to change a Cusomter's ID
    public function modifyIdIndex()
    {
        Log::debug('Route '.Route::currentRouteName().' visited by '.Auth::user()->full_name);
        return view('admin.customerID');
    }

    //  Submit the form to update a customer's ID
    public function updateID(Request $request)
    {
        Log::debug('Route '.Route::currentRouteName().' visited by '.Auth::user()->full_name.'. Submitted Data - ', $request->toArray());

        $request->validate([
            'original_id' => 'required|numeric|exists:customers,cust_id',
            'cust_id'     => 'nullable|numeric|unique:customers,cust_id',
        ]);

        Customers::find($request->original_id)->update([
            'cust_id' => $request->cust_id,
        ]);

        // Log::notice('Customer ID Updated for '.$data->name.'. Old Customer ID - '.$request->original_id.' New Customer ID - '.$request->cust_id);
        return response()->json(['success' => true]);
    }

    //  Form to view what kind of file types can be assigned to customers
    public function fileTypes()
    {
        Log::debug('Route '.Route::currentRouteName().' visited by '.Auth::user()->full_name);
        return view('admin.customerFileTypes');
    }

    //  Get the types of files that can be assigned to a customer file
    public function getFileTypes()
    {
        Log::debug('Route '.Route::currentRouteName().' visited by '.Auth::user()->full_name);

        $types = new CustomerFileTypesCollection(CustomerFileTypes::all());

        Log::debug('Customer File Types gathered - ', array($types));
        return $types;
    }

    //  Submit a new file type name
    public function submitNewFileType(Request $request)
    {
        Log::debug('Route '.Route::currentRouteName().' visited by '.Auth::user()->full_name.'. Submitted Data - ', $request->toArray());
        $request->validate([
            'name' => 'required',
        ]);

        CustomerFileTypes::create([
            'description' => $request->name,
        ]);

        Log::notice('New Customer File Type '.$request->name.' created by '.Auth::user()->full_name);
        return response()->json(['success' => true]);
    }

    //  Update the name of a file type
    public function setFileTypes(Request $request)
    {
        Log::debug('Route '.Route::currentRouteName().' visited by '.Auth::user()->full_name.'. Submitted Data - ', $request->toArray());
        $request->validate([
            'name' => 'required',
            'id'   => 'required|exists:customer_file_types,file_type_id',
        ]);

        CustomerFileTypes::find($request->id)->update([
            'description' => $request->name,
        ]);

        Log::notice('Customer File Type ID '.$request->id.' name updated to '.$request->name.' by '.Auth::user()->full_name);
        return response()->json(['success' => true]);
    }

    //  Try to delete a file type
    public function delFileType($id)
    {
        Log::debug('Route '.Route::currentRouteName().' visited by '.Auth::user()->full_name);

        try {
            //  Try to delete file type from database - will throw error if foreign key is in use
            CustomerFileTypes::find($id)->delete();
        } catch(\Illuminate\Database\QueryException $e) {
            //  Unable to remove file type  from the database
            Log::warning('Attempt to delete file type ID '.$id.' by User '.Auth::user()->full_name.' failed.  Reason - '.$e);
            return response()->json(['success' => false, 'reason' => 'In Use']);
        }

        Log::notice('Customer File Type ID '.$id.' deleted by '.Auth::user()->full_name);
        return response()->json(['success' => true]);
    }

    //  Show all disabled customers
    public function showDisabled()
    {
        Log::debug('Route '.Route::currentRouteName().' visited by '.Auth::user()->full_name);

        $custList = Customers::select('cust_id', 'name', 'deleted_at')
            ->onlyTrashed()
            ->get()
            ->makeVisible('deleted_at');

        Log::debug('Deactivated Customer data gathered - ', array($custList));
        return view('admin.customerDisabledList', ['custList' => $custList]);
    }

    //  Re-enable a customer
    public function enableCustomer($id)
    {
        Log::debug('Route '.Route::currentRouteName().' visited by '.Auth::user()->full_name);

        Customers::withTrashed()->where('cust_id', $id)->restore();

        Log::notice('Customer ID '.$id.' re-enabled by '.Auth::user()->full_name);
        return response()->json(['success' => true]);
    }
}
