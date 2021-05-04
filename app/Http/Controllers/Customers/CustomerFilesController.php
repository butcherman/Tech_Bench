<?php

namespace App\Http\Controllers\Customers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customers\CustomerFileRequest;
use App\Models\CustomerFile;
use App\Models\CustomerFileType;
use App\Models\FileUploads;
use App\Traits\FileTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CustomerFilesController extends Controller
{
    use FileTrait;

    protected $disk;

    public function __construct()
    {
        $this->disk = 'customers';
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
