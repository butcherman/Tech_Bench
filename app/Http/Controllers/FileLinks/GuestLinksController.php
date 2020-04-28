<?php

namespace App\Http\Controllers\FileLinks;

use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Log;

use App\Domains\FileLinks\GetFileLinkFiles;
use App\Domains\FileLinks\SaveFileLinkFile;
use App\Domains\FileLinks\GetFileLinkDetails;

use Illuminate\Http\Request;
use App\Http\Requests\AddFileLinkGuestFileRequest;

class GuestLinksController extends Controller
{
    //  Landing page if no link is sent
    public function index()
    {
        return view('links.guestIndex');
    }

    //  Show the link details for the user
    public function show($id)
    {
        $linkObj = new GetFileLinkDetails;
        $linkID = $linkObj->getLinkID($id);

        //  Determine which view should be returned to the guest
        if(!$linkID)
        {
            Log::warning('Visitor '.\Request::ip().' visited bad link Hash - '.$id);
            return view('links.guestBadLink');
        }
        else if($linkObj->isLinkExpired())
        {
            Log::warning('Visitor '.\Request::ip().' visited expired link Hash - '.$id);
            return view('links.guestExpiredLink');
        }
        else if($linkObj->isLinkDead())
        {
            Log::warning('Visitor '.\Request::ip().' visited a link that they cannot do anything with.  Hash - '.$id);
            return view('links.guestDeadLink');
        }

        return view('links.guestDetails', [
            'hash'         => $id,
            'instructions' => $linkObj->getLinkInstructions(),
            'hasFiles'     => $linkObj->hasGuestFiles(),
            'allowUp'      => $linkObj->canGuestUpload(),
        ]);
    }

    //  Get the guest available files for the link
    public function getFiles($id)
    {
        $linkID = (new GetFileLinkDetails)->getLinkID($id);
        return (new GetFileLinkFiles)->getGuestFiles($linkID);
    }

    //  Upload new file
    public function update(AddFileLinkGuestFileRequest $request, $id)
    {
        $request->linkID = (new GetFileLinkDetails)->getLinkID($id);
        //  Determine if a file is being uploaded still or not
        if((new SaveFileLinkFile)->execute($request, false))
        {
            return response()->json(['success' => true]);
        }

        return response()->json(['success' =>false]);
    }


    //  Notify the owner of the link that files were uploaded
    public function notify(Request $request, $id)
    {
        $linkObj = new GetFileLinkDetails;
        $linkObj->getLinkID($id);
        $linkData = $linkObj->execute();

        (new SaveFileLinkFile)->notifyOwnerOfUpload($linkData->user_id, $linkData);
        return response()->json(['success' => true]);
    }
}
