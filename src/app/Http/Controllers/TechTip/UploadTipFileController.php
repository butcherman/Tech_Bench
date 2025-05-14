<?php

namespace App\Http\Controllers\TechTip;

use App\Enums\DiskEnum;
use App\Http\Controllers\Controller;
use App\Http\Controllers\FileUploadController;
use App\Http\Requests\TechTip\TechTipRequest;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Inertia\Inertia;

class UploadTipFileController extends FileUploadController
{
    /**
     * Handle a file being uploaded for a new or updated Tech Tip.
     */
    public function __invoke(TechTipRequest $request): Response
    {
        if ($request->has('file')) {
            $this->setFileData(DiskEnum::tips, 'tmp');
            $savedFile = $this->getChunk($request->file('file'), $request);

            if ($savedFile) {
                session()->push('tip-file', $savedFile->file_id);
            }
        }

        return response()->noContent();
    }
}
