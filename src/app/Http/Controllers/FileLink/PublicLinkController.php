<?php

namespace App\Http\Controllers\FileLink;

use App\Enums\DiskEnum;
use App\Events\FileLink\FileUploadedFromPublicEvent;
use App\Http\Controllers\FileUploadController;
use App\Http\Requests\FileLink\PublicFileLinkRequest;
use App\Models\FileLink;
use App\Services\FileLink\FileLinkFileService;
use Inertia\Inertia;
use Inertia\Response;

class PublicLinkController extends FileUploadController
{
    /**
     * Show a Public File Link
     */
    public function show(FileLink $link): Response
    {
        $link->validatePublicLink();

        return Inertia::render('FileLink/Public/Show', [
            'link' => fn() => $link->only([
                'instructions',
                'allow_upload',
                'link_hash',
            ]),
            'files' => fn() => $link->Downloads->makeHidden('pivot'),
        ]);
    }

    /**
     * Upload files to a file link.
     */
    public function store(PublicFileLinkRequest $request, FileLinkFileService $svc, FileLink $link)
    {
        $this->setFileData(DiskEnum::links, $link->link_id);

        $savedFile = $this->getChunk($request->file('file'), $request);

        if ($savedFile) {
            $timeline = $svc->createFileLinkFile(
                $link,
                $savedFile,
                $request->get('name'),
                $request->session()->get('timeline', null)
            );

            $request->session()->put('timeline', $timeline->timeline_id);
        }

        return response()->noContent();
    }

    /**
     * Upload completed, notify the link owner of the new files.
     */
    public function update(PublicFileLinkRequest $request, FileLink $link)
    {
        FileUploadedFromPublicEvent::dispatch(
            $link,
            $request->session()->pull('timeline')
        );

        return back()->with('success', 'Files Uploaded');
    }
}
