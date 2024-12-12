<?php

namespace App\Http\Controllers\FileLink\Public;

use App\Enums\DiskEnum;
use App\Events\FileLink\FileUploadedFromPublicEvent;
use App\Http\Controllers\FileUploadController;
use App\Http\Requests\FileLink\PublicFileLinkRequest;
use App\Models\FileLink;
use App\Services\FileLink\FileLinkService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response as HttpResponse;
use Inertia\Inertia;
use Inertia\Response;

class PublicFileLinkController extends FileUploadController
{
    public function __construct(protected FileLinkService $svc) {}

    /**
     * Display the specified resource.
     */
    public function show(FileLink $link): Response
    {
        $link->validatePublicLink();

        return Inertia::render('Public/FileLinks/Show', [
            'link-data' => fn () => $link->only([
                'instructions',
                'allow_upload',
                'link_hash',
            ]),
            'link-files' => fn () => $link->FileUpload()
                ->wherePivot('upload', false)
                ->get()
                ->makeHidden('pivot'),
        ]);
    }

    /**
     * Upload a public file to the File Link
     */
    public function update(
        PublicFileLinkRequest $request,
        FileLink $link
    ): HttpResponse|RedirectResponse {
        $link->validatePublicLink();

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
            $request->get('name'),
            false
        );

        event(new FileUploadedFromPublicEvent($link));

        return back()->with('success', 'Files Uploaded Successfully');
    }
}
