<?php

namespace App\Http\Controllers\Customers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customers\CustomerFileRequest;
use App\Models\Customer;
use App\Models\CustomerFile;
use App\Models\CustomerFileType;
use Illuminate\Http\Request;
use App\Traits\FileTrait;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\File;


class CustomerFileController extends Controller
{
    use FileTrait;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created customer file
     */
    public function store(CustomerFileRequest $request)
    {
        $this->disk = 'customers';
        $this->folder = $request->input('cust_id');

        if($savedFile = $this->getChunk($request)) {        
            $request->checkForShared();
            $request->appendFileData($savedFile->file_id);

            CustomerFile::create($request->all());
        }

        return response()->noContent();
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
