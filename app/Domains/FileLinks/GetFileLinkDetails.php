<?php

namespace App\Domains\FileLinks;

use Illuminate\Support\Facades\Log;

use App\FileLinks;
use App\FileLinkFiles;

use App\Http\Resources\FileLinks as FileLinksResource;

class GetFileLinkDetails
{
    protected $linkID, $linkDetails;

    //  Constructor will set the ID of the link if it is available
    public function __construct($linkID = null)
    {
        $this->linkID = $linkID;
        if($this->linkID)
        {
            $this->linkDetails = FileLinks::find($this->linkID);
        }
    }

    //  Return the details of the file link
    public function execute($collection = false)
    {
        Log::debug('File link details gathered for '.$this->linkID.'.  Data gathered - ', array($this->linkDetails));
        if($collection)
        {
            return new FileLinksResource($this->linkDetails);
        }

        return $this->linkDetails;
    }

    //  Determines if the link is actually valid or not
    public function isLinkValid()
    {
        return $this->linkDetails ? true : false;
    }

    //  Determines if there is a customer attached to the link
    public function getLinkCustomer()
    {
        return $this->linkDetails->cust_id;
    }

    //  Retrieves the instructions assigned to the link
    public function getLinkInstructions()
    {
        return $this->linkDetails->note;
    }

    //  Retrieves the ID of the link based on the URL hash provided
    public function getLinkID($hash)
    {
        $data = FileLinks::where('link_hash', $hash)->first();

        if($data)
        {
            $this->linkID = $data->link_id;
            $this->linkDetails = $data;
            Log::debug('File link details gathered for '.$this->linkID.'.  Data gathered - ', array($this->linkDetails));
            return $this->linkID;
        }

        return false;
    }

    //  Determine if the link has expired or not
    public function isLinkExpired()
    {
        return $this->linkDetails->expire <= date('Y-m-d');
    }

    //  Determine if the link has files availabe for guest to download
    public function hasGuestFiles()
    {
        $files = FileLinkFiles::where('link_id', $this->linkID)->where('upload', false)->count();
        return $files > 0 ? true : false;
    }

    //  Determine if the guest can upload a file or not
    public function canGuestUpload()
    {
        return $this->linkDetails->allow_upload === 'Yes' ? true : false;
    }

    //  Determine if the link has no files to download, and the guest cannot upload files
    public function isLinkDead()
    {
        if(!$this->hasGuestFiles() && !$this->canGuestUpload())
        {
            return true;
        }

        return false;
    }
}
