<?php

namespace App\Http\Controllers\FileLink;

use App\Http\Controllers\Controller;
use App\Http\Requests\FileLink\FileLinkRequest;
use App\Http\Resources\FileLinkTableResource;
use App\Models\FileLink;
use App\Service\FileLink\FileLinkFileService;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response as HttpResponse;
use Inertia\Inertia;
use Inertia\Response;

class FileLinkController extends Controller
{
    public function __construct(protected FileLinkFileService $svc) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): Response
    {
        $this->authorize('viewAny', FileLink::class);

        return Inertia::render('FileLinks/Index', [
            'link-list' => fn () => $request->user()->FileLink,
        ]);
    }

    /**
     * Show the form for creating a new resource.
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
     * Store a newly created resource in storage.
     */
    public function store(FileLinkRequest $request): RedirectResponse|HttpResponse
    {
        // If this link has a file, process that first
        if ($request->has('file')) {
            $this->svc->processIncomingFile($request, null, true);

            return response()->noContent();
        }

        $newLink = $this->svc->createFileLink($request->collect());

        return redirect(route('links.show', $newLink->link_id))
            ->with('success', 'File Link Created');
    }

    /**
     * Display the specified resource.
     */
    public function show(FileLink $link): Response
    {
        $this->authorize('viewAny', FileLink::class);

        return Inertia::render('FileLinks/Show', [
            'link' => fn () => $link,
            'table-data' => fn () => FileLinkTableResource::make($link),
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
     * Show the form for editing the specified resource.
     */
    public function edit(FileLink $link): Response
    {
        $this->authorize('update', $link);

        return Inertia::render('FileLinks/Edit', [
            'link' => $link->mergeCasts(['expire' => 'datetime:Y-m-d']),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(FileLinkRequest $request, FileLink $link): RedirectResponse
    {
        $this->svc->updateFileLink($request->collect(), $link);

        return redirect(route('links.show', $link->link_id))
            ->with('success', 'Link Information Updated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(FileLink $link): RedirectResponse
    {
        $this->authorize('delete', $link);

        $this->svc->destroyFileLink($link);

        return redirect(route('links.index'))
            ->with('danger', 'File Link Deleted');
    }
}
