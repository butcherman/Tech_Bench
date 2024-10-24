<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Http\Requests\FileLink\PublicFileLinkRequest;
use App\Models\FileLink;
use App\Service\FileLink\FileLinkFileService;
use App\Traits\FileTrait;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response as HttpResponse;
use Inertia\Inertia;
use Inertia\Response;

class PublicFileLinkController extends Controller
{
    use FileTrait;

    public function __construct(protected FileLinkFileService $svc) {}

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

        // If a file is part of this request, process that first
        if ($request->has('file')) {
            $this->svc->processIncomingFile($request, $link, false);

            return response()->noContent();
        }

        $this->svc->savePublicLoadedFile($request->collect(), $link);

        return back()->with('success', 'Files Uploaded Successfully');
    }
}
