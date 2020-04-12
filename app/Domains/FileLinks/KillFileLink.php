<?php

namespace App\Domains\FileLinks;

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
        FileLinks::findOrFail($linkID)->update([
            'expire' => Carbon::yesterday()
        ]);

        return true;
    }

    //  Delete the file link and all attached files
    public function deleteFileLink($linkID)
    {
        $linkData = FileLinks::findOrFail($linkID);

        $this->removeLinkFiles();
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
                $fileID = $file->file_id;
                $file->delete();

                $this->deleteFile($fileID);
            }
        }
    }
}
