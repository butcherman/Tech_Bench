<?php

namespace App\Domains\FileLinks;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Notification;

use App\User;
use App\Files;
use App\FileLinkFiles;
use App\CustomerFiles;

use App\Domains\FilesDomain;

use App\Notifications\NewFileUpload;

use Pion\Laravel\ChunkUpload\Receiver\FileReceiver;
use Pion\Laravel\ChunkUpload\Handler\HandlerFactory;

use App\Http\Requests\MoveFileLinkFileToCustomerRequest;

class SaveFileLinkFile extends FilesDomain
{
    //  Execute will process an uploading file
    public function execute(Request $request, $new = true)
    {
        $this->receiver = new FileReceiver('file', $request, HandlerFactory::classFromRequest($request));

        $save = $this->receiver->receive();
        if($save->isFinished())
        {
            if(!$new)
            {
                $this->path = config('filesystems.paths.links').DIRECTORY_SEPARATOR.$request->linkID;
            }

            $fileID = $this->saveFile($save->getFile());

            if($new)
            {
                $fileArr = session('newLinkFile') != null ? session('newLinkFile') : [];
                $fileArr[] = $fileID;
                session(['newLinkFile' => $fileArr]);
            }
            else
            {
                if($request->name)
                {
                    $uploadDetails = [
                        'name' => $request->name,
                        'note' => $request->comments,
                    ];

                    $this->attachFile($request->linkID, $fileID, true, $uploadDetails);
                }
                else
                {
                    $this->attachFile($request->linkID, $fileID);
                }
            }

            return true;
        }

        return false;
    }

    //  Move a file in the link to a customers files folder
    public function moveFileToCustomer(MoveFileLinkFileToCustomerRequest $request, $linkID)
    {
        $linkCustomer = (new GetFileLinkDetails($linkID))->getLinkCustomer();
        $this->path = config('filesystems.paths.customers').DIRECTORY_SEPARATOR.$linkCustomer.DIRECTORY_SEPARATOR;

        if($this->checkForDup($request->fileID, $linkCustomer))
        {
            return false;
        }

        if(!$this->moveFile($this->path, $request->fileID))
        {
            return false;
        }

        $custFileData = CustomerFiles::create([
            'file_id'      => $request->fileID,
            'file_type_id' => $request->fileType,
            'cust_id'      => $linkCustomer,
            'user_id'      => Auth::user()->user_id,
            'name'         => $request->fileName
        ]);

        Log::info('File ID '.$request->fileID.' has been attached to Customer ID '.$linkCustomer.' by '.Auth::user()->full_name);
        return true;
    }

    //  Delete a file attached to file link
    public function deleteLinkFile($fileLinkID)
    {
        $fileData = FileLinkFiles::find($fileLinkID);
        $fileID   = $fileData->file_id;
        Log::notice('A file for a File Link has been deleted by '.Auth::user()->full_name.'.  File Data - ', array($fileData));
        $fileData->delete();

        $this->deleteFile($fileID);
    }

    //  Attach a file to a file link
    protected function attachFile($linkID, $fileID, $upload = false, $uploadDetails = null)
    {
        $fileData = FileLinkFiles::create([
            'link_id'  => $linkID,
            'file_id'  => $fileID,
            'user_id'  => $upload ? null : Auth::user()->user_id,
            'added_by' => $upload ? $uploadDetails['name'] : null,
            'upload'   => $upload,
            'note'     => $upload ? $uploadDetails['note'] : null,
        ]);

        Log::info('A new file has been attached to File Link ID '.$linkID.'.  File Data - ', array($fileData));
        return $fileData;
    }

    //  Move files from the default file location to the proper file link folder
    public function relocateFiles($linkID)
    {
        $files = session('newLinkFile');
        $this->path = config('filesystems.paths.links').DIRECTORY_SEPARATOR.$linkID;

        foreach($files as $file)
        {
            $this->moveFile($this->path, $file);
            $this->attachFile($linkID, $file);
        }

        session()->forget('newLinkFile');
    }

    //  When a guest uploads a new file, the owner is notified
    public function notifyOwnerOfUpload($userID, $linkDetails)
    {
        $userData = User::find($userID);
        Notification::send($userData, new NewFileUpload($linkDetails));

        return true;
    }

    protected function checkForDup($fileID, $custID)
    {
        $fileData = Files::find($fileID);

        $dup = CustomerFiles::where('file_id', $fileData->fileID)->where('cust_id', $custID)->count();
        if($dup || Storage::exists($this->path.$fileData->file_name))
        {
            return true;
        }

        return false;
    }
}
