<?php

namespace App\Http\Controllers\Customers;

use App\Http\Controllers\Controller;

use App\Domains\Customers\GetCustomerFiles;
use App\Domains\Customers\SetCustomerFiles;
use App\Domains\FileTypes\GetCustomerFileTypes;

use App\Http\Requests\CustomerFileNewRequest;
use App\Http\Requests\CustomerFileUpdateRequest;

class CustomerFilesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    //  Index funtion will return an array of file types that can be assigned to a file
    public function index()
    {
        return (new GetCustomerFileTypes)->execute(true);
    }

    //  Store a new customer file
    public function store(CustomerFileNewRequest $request)
    {
        //  Determine if a file is being uploaded still or not
        if((new SetCustomerFiles($request->cust_id))->createFile($request))
        {
            return response()->json(['success' => true]);
        }

        return response()->json(['success' =>false]);
    }

    //  Get the files for the customer
    public function show($id)
    {
        return (new GetCustomerFiles($id))->execute();
    }

    //  Update the information of the file, but not the file itself
    public function update(CustomerFileUpdateRequest $request, $id)
    {
        (new SetCustomerFiles($request->cust_id))->updateFile($request, $id);
        return response()->json(['success' => true]);
    }

    //  Remove a customer file
    public function destroy($id)
    {
        (new SetCustomerFiles($id))->deleteCustFile($id);
        return response()->json(['success' => true]);
    }
}
