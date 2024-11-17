<?php

namespace App\Http\Controllers\Home;

use App\Actions\Misc\ProcessUploadedImage;
use App\Http\Controllers\Controller;
use App\Http\Requests\Home\UploadImageRequest;

class UploadImageController extends Controller
{
    /**
     * Handle an uploaded image from the TinyMCE Text Editor
     */
    public function __invoke(UploadImageRequest $request, ProcessUploadedImage $svc, ?string $folderName = null)
    {
        // $path = 'images/uploaded/'.$folderName;
        // $location = Storage::disk('public')
        //     ->putFile($path, new File($request->file));

        // return ['location' => Storage::url($location)];

        $path = $svc($request->file, $folderName);

        return ['location' => $path];
    }
}
