<?php

namespace App\Domains\FileLinks;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

use App\FileLinks;

use App\Domains\FileLinks\SaveFileLinkFile;

use App\Http\Requests\NewFileLinkRequest;

class CreateFileLink extends SaveFileLinkFile
{
    //  Create a brand new file link and determine if any files should be linked to it
    public function create(NewFileLinkRequest $request)
    {
        $linkID = $this->createLink($request);

        if($request->session()->has('newLinkFile'))
        {
            $this->relocateFiles($linkID);
        }

        return ['link' => $linkID, 'name' => Str::slug($request->name)];
    }

    //  Build a new link data in the database
    protected function createLink($data)
    {
        $link = FileLinks::create([
            'user_id'      => Auth::user()->user_id,
            'cust_id'      => $data->customerID,
            'link_hash'    => $this->generateHash(),
            'link_name'    => $data->name,
            'expire'       => $data->expire,
            'allow_upload' => isset($data->allowUp) && $data->allowUp ? true : false,
            'note'         => $data->instructions
        ]);

        return $link->link_id;
    }

    //  Generate a random hash to use as the link.  Verify it is not already in use
    protected function generateHash()
    {
        do
        {
            $hash = strtolower(Str::random(15));
            $dup  = FileLinks::where('link_hash', $hash)->get()->count();
        } while($dup != 0);

        return $hash;
    }
}
