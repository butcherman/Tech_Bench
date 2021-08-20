<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Http\Requests\Home\UploadFilerequest;
use Illuminate\Http\Request;
use App\Traits\FileTrait;

class UploadFileController extends Controller
{
    use FileTrait;

    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(UploadFilerequest $request)
    {
        $status = $this->getChunk($request, $request->disk, $request->folder);

        //  If the file is completely uploaded, save the name and location in session data and move onto the next file
        if($status['done'] === 100)
        {
            $newFile = FileUploads::create([
                'disk'      => $request->disk,
                'folder'    => $request->folder,
                'file_name' => $status['filename'],
                'public'    => $request->public,
            ]);

            $fileArr = session('new-file-upload') !== null ? session('new-file-upload') : [];
            $fileArr[] = $newFile;

            session(['new-file-upload' => $fileArr]);

            return response()->noContent();
        }

        //  Continue the file upload
        return response($status);
    }
}
