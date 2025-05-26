<?php

namespace App\Http\Controllers\FileLink;

use App\Enums\DiskEnum;
use App\Http\Controllers\Controller;
use App\Http\Controllers\FileUploadController;
use App\Http\Requests\FileLink\FileLinkRequest;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Inertia\Inertia;

class UploadLinkFileController extends FileUploadController
{
    /**
     * Upload a file to a File Link.
     */
    public function __invoke(FileLinkRequest $request): Response
    {
        $this->setFileData(DiskEnum::links, 'tmp', true);

        if ($request->has('file')) {

            $savedFile = $this->getChunk($request->file('file'), $request);

            if ($savedFile) {
                session()->push('link-file', $savedFile->file_id);
            }
        }

        return response()->noContent();
    }
}
