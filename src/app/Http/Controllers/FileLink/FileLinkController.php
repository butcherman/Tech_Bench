<?php

namespace App\Http\Controllers\FileLink;

use App\Enums\DiskEnum;
use App\Http\Controllers\FileUploadController;
use App\Http\Requests\FileLink\FileLinkRequest;
use App\Models\FileLink;
use App\Services\FileLink\FileLinkService;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response as HttpResponse;
use Inertia\Inertia;
use Inertia\Response;

class FileLinkController extends FileUploadController
{
    public function __construct(protected FileLinkService $svc) {}

    /**
     * Display a listing of the Users File Links.
     */
    public function index(Request $request): Response
    {
        $this->authorize('viewAny', FileLink::class);

        return Inertia::render('FileLinks/Index', [
            'link-list' => fn () => $request->user()->FileLinks,
        ]);
    }

    /**
     * Show the form for creating a new File Link.
     */
    public function create(): Response
    {
        $this->authorize('viewAny', FileLink::class);

        return Inertia::render('FileLinks/Create', [
            'default-expire' => Carbon::now()
                ->addDays(config('file-link.default_link_life'))
                ->format('Y-m-d'),
        ]);
    }

    /**
     * Store a newly created File Link.
     */
    public function store(FileLinkRequest $request): RedirectResponse|HttpResponse
    {
        // If this link has a file, process that first
        if ($request->has('file')) {
            $this->setFileData(DiskEnum::links, 'tmp');
            $savedFile = $this->getChunk($request->file('file'), $request);

            if ($savedFile) {
                session()->push('link-file', $savedFile->file_id);
            }

            return response()->noContent();
        }

        $fileList = $request->session()->has('link-file')
            ? $request->session()->pull('link-file')
            : [];

        $newLink = $this->svc->createFileLink(
            $request->safe()->collect(),
            $request->user(),
            $fileList
        );

        return redirect(route('links.show', $newLink->link_id))
            ->with('success', 'File Link Created');
    }

    /**
     * Display the File Link.
     */
    public function show(FileLink $link): Response
    {
        $this->authorize('view', $link);

        return Inertia::render('FileLinks/Show', [
            'link' => fn () => $link,
            'table-data' => fn () => [
                'link_name' => $link->link_name,
                'expire' => $link->expire->format('M d, Y'),
                'allow_upload' => $link->allow_upload,
                'has_instructions' => $link->instructions ? true : false,
            ],
            'timeline' => fn () => $link->Timeline
                ->load(['FileUpload', 'FileLinkNote']),
            'downloadable-files' => fn () => $link->FileUpload()
                ->wherePivot('upload', false)
                ->get()
                ->makeVisible(['created_at']),
            'uploaded-files' => fn () => $link->FileUpload()
                ->wherePivot('upload', true)
                ->get()
                ->makeVisible(['created_at']),
        ]);
    }

    /**
     * Show the form for editing the File Link.
     */
    public function edit(FileLink $link): Response
    {
        $this->authorize('update', $link);

        return Inertia::render('FileLinks/Edit', [
            'link' => $link->mergeCasts(['expire' => 'datetime:Y-m-d']),
        ]);
    }

    /**
     * Update the File Link.
     */
    public function update(FileLinkRequest $request, FileLink $link): RedirectResponse
    {
        $this->svc->updateFileLink($request->collect(), $link);

        return redirect(route('links.show', $link->link_id))
            ->with('success', 'Link Information Updated');
    }

    /**
     * Delete a File Link.
     */
    public function destroy(FileLink $link): RedirectResponse
    {
        $this->authorize('delete', $link);

        $this->svc->destroyFileLink($link);

        return redirect(route('links.index'))
            ->with('danger', 'File Link Deleted');
    }
}
