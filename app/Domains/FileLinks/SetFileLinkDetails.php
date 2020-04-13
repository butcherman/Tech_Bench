<?php

namespace App\Domains\FileLinks;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

use App\FileLinks;

use App\Http\Requests\UpdateFileLinkRequest;
use App\Http\Requests\UpdateFileLinkInstructionsRequest;

class SetFileLinkDetails
{
    //  Execute will process an uploading file
    public function execute(UpdateFileLinkRequest $request, $linkID)
    {
        $linkData = FileLinks::find($linkID)->update([
            'link_name'    => $request->name,
            'expire'       => $request->expire,
            'allow_upload' => $request->allowUp,
            'cust_id'      => $request->customerID,
        ]);

        Log::info('File Link ID '.$linkID.' has been updated by '.Auth::user()->full_name.'.  Link Data - ', array($linkData));
        return true;
    }

    //  Update only the instructions attached to the link
    public function setLinkInstructions(UpdateFileLinkInstructionsRequest $request, $linkID)
    {
        $inst = FileLinks::find($linkID)->update([
            'note' => $request->instructions,
        ]);

        Log::info('Instructions for File Link ID '.$linkID.' have been updated by '.Auth::user()->full_name.'.  Instruction Details - ', array($request));
        return true;
    }
}
