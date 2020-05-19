<?php

namespace App\Domains\FileLinks;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

use App\FileLinks;
use App\FileLinkFiles;

use App\Domains\FilesDomain;

use App\Http\Requests\FileLinkCreateRequest;
use App\Http\Requests\FileLinkUpdateRequest;
use App\Http\Requests\FileLinkInstructionsRequest;

class SetFileLinkDetails extends FilesDomain
{
    //  Create a new File Link
    public function processNewLink(FileLinkCreateRequest $request)
    {
        if(isset($request->file))
        {
            $fileID = $this->processFileChunk($request);
            if($fileID)
            {
                $fileArr = session('newLinkFile') != null ? session('newLinkFile') : [];
                $fileArr[] = $fileID;
                session(['newLinkFile' => $fileArr]);
            }

            return false;
        }

        return $this->createLink($request);
    }

    //  Update the file link
    public function updateLink(FileLinkUpdateRequest $request, $linkID)
    {
        $linkData = FileLinks::find($linkID)->update([
            'link_name'    => $request->name,
            'expire'       => $request->expire,
            'allow_upload' => $request->allow_upload,
            'cust_id'      => $request->cust_id,
        ]);

        Log::info('File Link ID '.$linkID.' has been updated by '.Auth::user()->full_name.'.  Link Data - ', array($linkData));
        return true;
    }

    //  Update only the instructions attached to the link
    public function setLinkInstructions(FileLinkInstructionsRequest $request, $linkID)
    {
        FileLinks::find($linkID)->update([
            'note' => $request->instructions,
        ]);

        Log::info('Instructions for File Link ID '.$linkID.' have been updated by '.Auth::user()->full_name.'.  Instruction Details - ', array($request));
        return true;
    }

    //  Create the new File Link
    protected function createLink($linkData)
    {
        $link = FileLinks::create([
            'user_id'      => Auth::user()->user_id,
            'cust_id'      => $linkData->customerID,
            'link_hash'    => $this->generateHash(),
            'link_name'    => $linkData->name,
            'expire'       => $linkData->expire,
            'allow_upload' => isset($linkData->allowUp) && $linkData->allowUp ? true : false,
            'note'         => $linkData->instructions
        ]);

        $this->processFiles($link->link_id);
        Log::info('User '.Auth::user()->full_name.' created new file link.  Data - ', array($link));
        return $link->link_id;
    }

    //  Generate a random hash to use as the link.  Verify it is not already in use
    protected function generateHash()
    {
        do
        {
            $hash = strtolower(Str::random(15));
            $dup  = FileLinks::where('link_hash', $hash)->get()->count();
            Log::debug('New hash created - '.$hash.'.  Checking for duplicate.  Result - '.$dup);
        } while($dup != 0);

        return $hash;
    }

    //  For all files that were uploaded, move to the proper folder and attach to the tip
    protected function processFiles($linkID)
    {
        if(session('newLinkFile') != null)
        {
            $files = session('newLinkFile');
            // $this->path = config('filesystems.paths.tips').DIRECTORY_SEPARATOR.$tipID;
            $this->path = config('filesystems.paths.links').DIRECTORY_SEPARATOR.$linkID;
            foreach($files as $file)
            {
                $this->moveFile($this->path, $file);

                //  Attach file to Tech Tip
                FileLinkFiles::create([
                    'link_id' => $linkID,
                    'file_id' => $file,
                ]);
            }

            session()->forget('newLinkFile');
        }

        return true;
    }
}
