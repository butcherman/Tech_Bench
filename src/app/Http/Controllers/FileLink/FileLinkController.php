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
     * Show a listing of the user's File Links.
     */
    public function index(Request $request): Response
    {
        $this->authorize('viewAny', FileLink::class);

        return Inertia::render('FileLink/Index', [
            'link-list' => Inertia::defer(fn() => $request->user()->FileLinks),
        ]);
    }

    /**
     * Show form to create a new File Link.
     */
    public function create(): Response
    {
        $this->authorize('create', FileLink::class);

        return Inertia::render('FileLink/Create', [
            'default-expire' => fn() => Carbon::now()
                ->addDays(config('file-link.default_link_life'))
                ->format('Y-m-d'),
        ]);
    }

    /**
     * Save a new File Link and any attached files.
     */
    public function store(FileLinkRequest $request): HttpResponse|RedirectResponse
    {
        $newLink = $this->svc->createFileLink(
            $request->safe()->collect(),
            $request->user(),
            session()->pull('link-file', [])
        );

        return redirect(route('links.show', $newLink->link_id))
            ->with('success', 'File Link Created');
    }

    /**
     * Show details for a File Link
     */
    public function show(Request $request, FileLink $link): Response
    {
        $this->authorize('view', $link);

        return Inertia::render('FileLink/Show', [
            'link' => fn() => $link,
            'is-admin' => fn() => $request->user()->can('manage', $link)
                && $link->user_id !== $request->user()->user_id,

            /**
             * Deferred Props
             */
            'timeline' => Inertia::defer(fn() => $link->Timeline),
            'uploads' => Inertia::defer(fn() => $link->Uploads),
            'downloads' => Inertia::defer(fn() => $link->Downloads),
        ]);
    }

    /**
     * Edit the details for a File Link
     */
    public function edit(FileLink $link): Response
    {
        $this->authorize('update', $link);

        return Inertia::render('FileLink/Edit', [
            'link' => fn() => $link->mergeCasts(['expire' => 'datetime:Y-m-d']),
        ]);
    }

    /**
     * Update the details of a File Link
     */
    public function update(FileLinkRequest $request, FileLink $link): RedirectResponse
    {
        $this->svc->updateFileLink($request->safe()->collect(), $link);

        return redirect(route('links.show', $link->link_id))
            ->with('success', 'Link Details Updated');
    }

    /**
     * Delete a file link and all attached files.
     */
    public function destroy(FileLink $link): RedirectResponse
    {
        $this->authorize('delete', $link);

        $this->svc->destroyFileLink($link);

        return redirect(route('links.index'))->with('danger', 'Link Deleted');
    }
}
