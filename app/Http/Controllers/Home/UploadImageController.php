<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Http\Requests\Home\UploadImageRequest;
use Illuminate\Http\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class UploadImageController extends Controller
{
    /**
     *  Upload an image that will be used in a text editor field
     */
    public function __invoke(UploadImageRequest $request)
    {
        //  Storage Path
        $path = 'images/uploaded';
        $location = Storage::disk('public')->putFile($path, new File($request->file));

        Log::channel('user')->info('New image uploaded by '.$request->user()->username, $request->toArray());

        return Storage::url($location);
    }
}
