<?php

namespace App\Http\Controllers\TechTip;

use App\Enums\DiskEnum;
use App\Http\Controllers\FileUploadController;
use App\Http\Requests\TechTip\TechTipRequest;
use Illuminate\Http\Response;

class UploadTechTipFileController extends FileUploadController
{
    /**
     * Handle files being uploaded to a Tech Tip.
     */
    public function __invoke(TechTipRequest $request): Response
    {
        $this->setFileData(DiskEnum::tips, 'tmp');
        $savedFile = $this->getChunk($request->file('file'), $request);

        if ($savedFile) {
            session()->push('tip-file', $savedFile->file_id);
        }

        return response()->noContent();
    }
}
