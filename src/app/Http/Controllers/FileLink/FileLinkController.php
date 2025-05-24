<?php

namespace App\Http\Controllers\FileLink;

use App\Enums\DiskEnum;
use App\Http\Controllers\Controller;
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
            'default-expire' => fn() =>  Carbon::now()
                ->addDays(config('file-link.default_life_link'))
                ->format('Y-m-d'),
        ]);
    }

    /**
     * Save a new File Link and any attached files.
     */
    public function store(FileLinkRequest $request): HttpResponse|RedirectResponse
    {
        if ($request->has('file')) {
            $this->setFileData(DiskEnum::links, 'tmp');
            $savedFile = $this->getChunk($request->file('file'), $request);

            if ($savedFile) {
                session()->push('link-file', $savedFile->file_id);
            }

            return response()->noContent();
        }

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
    public function show(FileLink $link): Response
    {
        $this->authorize('view', $link);

        return Inertia::render('FileLink/Show', [
            'link' => fn() => $link,
        ]);
    }

    /**
     *
     */
    public function edit(string $id)
    {
        //
        // return 'edit';
        return Inertia::render('FileLink/Edit');
    }

    /**
     *
     */
    public function update(Request $request, string $id)
    {
        //
        return 'update';
    }

    /**
     *
     */
    public function destroy(FileLink $link): RedirectResponse
    {
        $this->authorize('delete', $link);

        $this->svc->destroyFileLink($link);

        return redirect(route('links.index'))->with('danger', 'Link Deleted');
    }
}
