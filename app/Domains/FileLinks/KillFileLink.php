<?php

namespace App\Domains\FileLinks;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

use Carbon\Carbon;

use App\FileLinks;
use App\FileLinkFiles;

use App\Domains\FilesDomain;

class KillFileLink extends FilesDomain
{
    protected $linkID;

    //  Only disable the link by adjusting the expire date
    public function disableLink($linkID)
    {
        $linkData = FileLinks::findOrFail($linkID)->update([
            'expire' => Carbon::yesterday()
        ]);

        Log::info('File Link has been disabled by '.Auth::user()->full_name.'.  Link Data - ', array($linkData));
        return true;
    }

    //  Delete the file link and all attached files
    public function deleteFileLink($linkID)
    {
        $linkData = FileLinks::findOrFail($linkID);

        $this->removeLinkFiles();
        Log::notice('File Link has been deleted by '.Auth::user()->full_name.'.  Link Data - ', array($linkData));
        $linkData->delete();

        return true;
    }

    //  Remove all of the files attached to the link
    protected function removeLinkFiles()
    {
        $fileList = FileLinkFiles::where('link_id', $this->linkID);

        if($fileList)
        {
            foreach($fileList as $file)
            {
                Log::debug('File from File Link being deleted.  File Data - ', array($file));
                $fileID = $file->file_id;
                $file->delete();

                $this->deleteFile($fileID);
            }
        }
    }
}
