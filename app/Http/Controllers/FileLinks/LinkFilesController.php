<?php

namespace App\Http\Controllers\FileLinks;

use App\Http\Controllers\Controller;

use App\Domains\FileLinks\GetFileLinkFiles;
use App\Domains\FileLinks\SaveFileLinkFile;

use App\Http\Requests\AddFileLinkFileRequest;
use App\Http\Requests\MoveFileLinkFileToCustomerRequest;

class LinkFilesController extends Controller
{
    public function __construct()
    {
        //  Verify the user is logged in and has permissions for this page
        $this->middleware('auth');
        $this->middleware(function($request, $next) {
            $this->authorize('hasAccess', 'Use File Links');
            return $next($request);
        });
    }

    //  Add a file to the file link
    public function store(AddFileLinkFileRequest $request)
    {
        //  Determine if a file is being uploaded still or not
        if((new SaveFileLinkFile)->execute($request, false))
        {
            return response()->json(['success' => true]);
        }

        return response()->json(['success' =>false]);
    }

    //  Show the files attached to a link
    public function show($id)
    {
        return (new GetFileLinkFiles)->execute($id, true);
    }

    //  Move a file to a customer file
    public function update(MoveFileLinkFileToCustomerRequest $request, $id)
    {
        $moveObj = new SaveFileLinkFile;
        $success = $moveObj->moveFileToCustomer($request, $id);
        return response()->json(['success' => $success]);
    }

    //  Delete a file attached to a link
    public function destroy($id)
    {
        (new SaveFileLinkFile)->deleteLinkFile($id);
        return response()->json(['success' => true]);
    }
}
