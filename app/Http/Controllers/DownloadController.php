<?php

namespace App\Http\Controllers;

use App\Models\FileUploads;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DownloadController extends Controller
{
    /**
     *  Download a file from the database
     */
    public function __invoke($id, $name)
    {
        //  Make sure that the file actually exists in the database - both file ID and name must match
        $file = FileUploads::where('file_id', $id)->where('file_name', $name)->firstOrFail();

        //  Determine if the person downloading the file is allowed to download it
        if(!Auth::check() && !$file->public)
        {
            abort(403, 'You Do Not Have Permission To Download This File');
        }

        //  Determine that the file itself exists
        if(!Storage::disk($file->disk)->exists($file->folder.DIRECTORY_SEPARATOR.$file->file_name))
        {
            abort(404, 'Cannot Find the File Specified');
        }

        return Storage::disk($file->disk)->download($file->folder.DIRECTORY_SEPARATOR.$file->file_name);
    }
}
