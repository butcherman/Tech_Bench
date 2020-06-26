<?php

namespace App\Http\Controllers\Customers;

use App\Domains\Files\GetCustomerFileTypes;
use App\Domains\Customers\getCustomerFiles;
use App\Domains\Customers\setCustomerFiles;

use App\Http\Controllers\Controller;

use App\Http\Requests\Customers\NewFileRequest;
use App\Http\Requests\Customers\EditFileRequest;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CustomerFilesController extends Controller
{
    //  Index will get the types of files that can be assigned to a customer
    public function index()
    {
        return (new GetCustomerFileTypes)->execute();
    }

    //  Store a new customer file
    public function store(NewFileRequest $request)
    {
        if((new setCustomerFiles)->createFile($request, Auth::user()->user_id))
        {
            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false]);
    }

    //  Get the customers files
    public function show($id)
    {
        return (new getCustomerFiles)->execute($id);
    }

    //  Update the basic information of a file without changing the physical file
    public function update(EditFileRequest $request, $id)
    {
        (new setCustomerFiles)->updateFile($request, $id);
        return response()->json(['success' => true]);
    }

    //  Delete a file
    public function destroy($id)
    {
        (new SetCustomerFiles)->deleteCustFile($id);
        Log::info('User '.Auth::user()->full_name.' deleted file ID '.$id);
        return response()->json(['success' => true]);
    }
}
