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
            'link' => fn () => $link->only([
                'instructions',
                'allow_upload',
                'link_hash',
            ]),
            'files' => fn () => $link->Downloads->makeHidden('pivot'),
        ]);
    }

    public function update(PublicFileLinkRequest $request, FileLinkFileService $svc, FileLink $link)
    {
        $this->setFileData(DiskEnum::links, $link->link_id);

        $savedFile = $this->getChunk($request->file('file'), $request);

        if ($savedFile) {
            $svc->createFileLinkFile($link, $savedFile, $request->get('name'));
            // TODO - Determine when upload is done and send email
            // FileUploadedFromPublicEvent::dispatch($link, $savedFile);
        }

        return response()->noContent();
    }
}
