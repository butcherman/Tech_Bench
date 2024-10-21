<?php

// TODO - Refactor

namespace App\Http\Controllers\FileLink;

use App\Http\Controllers\Controller;
use App\Models\FileLink;
use App\Models\FileLinkFile;
use App\Service\FileLink\FileLinkFileService;
use App\Traits\FileTrait;
use Illuminate\Http\Request;

class FileLinkFileController extends Controller
{
    use FileTrait;

    public function __construct(protected FileLinkFileService $svc) {}

    /**
     * Store a File Link File and attach it to the File Link.
     */
    public function store(Request $request, FileLink $link)
    {
        $this->authorize('update', $link);

        $this->svc->processIncomingFile($request, $link);

        return response()->noContent();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(FileLink $link, FileLinkFile $linkFile)
    {
        $this->authorize('update', $link);

        $this->svc->destroyLinkFile($linkFile);

        return back()->with('warning', 'File Deleted');
    }
}
