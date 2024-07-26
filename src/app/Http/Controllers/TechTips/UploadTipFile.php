<?php

namespace App\Http\Controllers\TechTips;

use App\Http\Controllers\Controller;
use App\Http\Requests\TechTips\TechTipRequest;
use App\Traits\FileTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class UploadTipFile extends Controller
{
    use FileTrait;

    /**
     * Handle the incoming request.
     */
    public function __invoke(TechTipRequest $request)
    {
        $this->setFileData('tips', 'tmp');
        if ($request->file) {

            if ($savedFile = $this->getChunk($request)) {
                Log::debug('Tech Tip file saved', $savedFile->toArray());
                $request->session()->push('tip-file', $savedFile->file_id);
                Log::debug('Current session file list', $request->session()->get('tip-file'));
            }
        }

        return response()->noContent();
    }
}
