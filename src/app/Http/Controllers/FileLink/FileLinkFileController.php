<?php

namespace App\Http\Controllers\FileLink;

use App\Http\Controllers\Controller;
use App\Models\FileLink;
use App\Models\FileLinkFile;
use App\Service\FileLink\FileLinkFileService;
use App\Traits\FileTrait;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class FileLinkFileController extends Controller
{
    use FileTrait;

    public function __construct(protected FileLinkFileService $svc) {}

    /**
     * Store a File Link File and attach it to the File Link.
     */
    public function store(Request $request, FileLink $link): Response
    {
        $this->authorize('update', $link);

        if ($request->has('file')) {
            $this->svc->processIncomingFile($request, $link, true);

            return response()->noContent();
        }

        $this->svc->savePrivateLoadedFile($link);

        return response()->noContent();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(FileLink $link, FileLinkFile $linkFile): RedirectResponse
    {
        $this->authorize('update', $link);

        $this->svc->destroyLinkFile($linkFile);

        return back()->with('warning', 'File Deleted');
    }
}
