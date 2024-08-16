<?php

namespace App\Http\Controllers\FileLink;

use App\Http\Controllers\Controller;
use App\Http\Requests\FileLink\FileLinkRequest;
use App\Traits\FileTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class UploadFileController extends Controller
{
    use FileTrait;

    /**
     * Handle the incoming request.
     */
    public function __invoke(FileLinkRequest $request)
    {
        $this->setFileData('fileLinks', 'tmp');

        if ($request->file) {
            if ($savedFile = $this->getChunk($request)) {
                Log::debug('File Link file saved', $savedFile->toArray());
                $request->session()->push('link-file', $savedFile->file_id);
                Log::debug(
                    'Current session file list',
                    $request->session()->get('link-file')
                );
            }
        }

        return response()->noContent();
    }
}
