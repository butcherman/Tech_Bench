<?php

namespace App\Domains\FileLinks;

use App\FileLinks;

use App\Http\Requests\UpdateFileLinkRequest;
use App\Http\Requests\UpdateFileLinkInstructionsRequest;

class SetFileLinkDetails
{
    //  Execute will process an uploading file
    public function execute(UpdateFileLinkRequest $request, $linkID)
    {
        FileLinks::find($linkID)->update([
            'link_name'    => $request->name,
            'expire'       => $request->expire,
            'allow_upload' => $request->allowUp,
            'cust_id'      => $request->customerID,
        ]);

        return true;
    }

    //  Update only the instructions attached to the link
    public function setLinkInstructions(UpdateFileLinkInstructionsRequest $request, $linkID)
    {
        FileLinks::find($linkID)->update([
            'note' => $request->instructions,
        ]);

        return true;
    }
}
