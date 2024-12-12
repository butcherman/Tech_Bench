<?php

namespace App\Http\Controllers\FileLink;

use App\Enums\DiskEnum;
use App\Http\Controllers\FileUploadController;
use App\Models\FileLink;
use App\Models\FileLinkFile;
use App\Services\FileLink\FileLinkService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class FileLinkFileController extends FileUploadController
{
    public function __construct(protected FileLinkService $svc) {}

    /**
     * Store a newly created Model.
     */
    public function store(Request $request, FileLink $link): Response
    {
        $this->authorize('update', $link);

        // If the upload is in progress, process that first.
        if ($request->has('file')) {
            $this->setFileData(DiskEnum::links, $link->link_id, true);
            $savedFile = $this->getChunk($request->file('file'), $request);

            if ($savedFile) {
                session()->push('link-file', $savedFile->file_id);
            }

            return response()->noContent();
        }

        $this->svc->addFileLinkFile(
            $link,
            session()->pull('link-file'),
            $request->user()->user_id,
            true
        );

        return response()->noContent();
    }

    /**
     * Remove the Model.
     */
    public function destroy(FileLink $link, FileLinkFile $linkFile): RedirectResponse
    {
        $this->authorize('update', $link);

        $this->svc->removeFileLinkFile($linkFile);

        return back()->with('warning', 'File Deleted');
    }
}
