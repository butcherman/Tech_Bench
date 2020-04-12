<?php

namespace App\Domains\FileLinks;

use App\FileLinkFiles;

use App\Http\Resources\FileLinkFilesCollection;

class GetFileLinkFiles
{
    //  Execute will process an uploading file
    public function execute($linkID, $collection = false)
    {
        $files = FileLinkFiles::where('link_id', $linkID)
                    ->orderBy('user_id', 'ASC')
                    ->orderBy('created_at', 'ASC')
                    ->with('Files')
                    ->with('User')
                    ->get();

        if($collection)
        {
            return new FileLinkFilesCollection($files);
        }

        return $files;
    }

    //  Only retrieve the files that the guest can download
    public function getGuestFiles($linkID)
    {
        $files = new FileLinkFilesCollection(
            FileLinkFiles::where('link_id', $linkID)
                ->where('upload', 0)
                ->orderBy('created_at', 'ASC')
                ->with('Files')
                ->get()
        );

        return $files;
    }
}
