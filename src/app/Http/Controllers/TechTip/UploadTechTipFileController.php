<?php

namespace App\Http\Controllers\TechTip;

use App\Enums\DiskEnum;
use App\Http\Controllers\FileUploadController;
use App\Http\Requests\TechTip\TechTipRequest;

class UploadTechTipFileController extends FileUploadController
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(TechTipRequest $request)
    {
        $this->setFileData(DiskEnum::tips, 'tmp');
        $savedFile = $this->getChunk($request->file('file'), $request);

        if ($savedFile) {
            session()->push('tip-file', $savedFile->file_id);
        }

        return response()->noContent();
    }
}
