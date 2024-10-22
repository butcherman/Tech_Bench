<?php

namespace App\Http\Controllers\FileLink;

use App\Http\Controllers\Controller;
use App\Http\Requests\FileLink\FileLinkRequest;
use App\Service\FileLink\FileLinkFileService;
use App\Traits\FileTrait;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class UploadFileController extends Controller
{
    use FileTrait;

    public function __construct(protected FileLinkFileService $svc) {}

    /**
     * Handle the incoming request.
     */
    public function __invoke(FileLinkRequest $request): Response
    {
        $this->svc->processIncomingFile($request, null, true);

        return response()->noContent();
    }
}
