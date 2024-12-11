<?php

namespace App\Http\Controllers\FileLink;

use App\Http\Controllers\Controller;
use App\Http\Resources\FileLinkAdministrationResource;
use App\Models\FileLink;
use App\Services\FileLink\FileLinkService;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class FileLinkAdministrationController extends Controller
{
    public function __construct(protected FileLinkService $svc) {}

    /**
     * Display a listing all File Links for all users.
     */
    public function index(): Response
    {
        $this->authorize('manage', FileLink::class);

        return Inertia::render('FileLinks/Manage/Index', [
            'link-list' => FileLinkAdministrationResource::collection(
                FileLink::all()
            ),
        ]);
    }

    /**
     * Display another users File Link.
     */
    public function show(FileLink $link): Response
    {
        $this->authorize('manage', FileLink::class);

        return Inertia::render('FileLinks/Manage/Show', [
            'link' => $link,
            'table-data' => $link->only(['link_name', 'expire', 'allow_upload']),
            'timeline' => $link->Timeline->load(['FileUpload', 'FileLinkNote']),
            'downloadable-files' => $link->FileUpload()
                ->wherePivot('upload', false)
                ->get()
                ->makeVisible(['created_at']),
            'uploaded-files' => $link->FileUpload()
                ->wherePivot('upload', true)
                ->get()
                ->makeVisible(['created_at']),
        ]);
    }

    /**
     * Remove another users File Link.
     */
    public function destroy(FileLink $link): RedirectResponse
    {
        $this->authorize('delete', $link);

        $this->svc->destroyFileLink($link);

        return redirect(route('admin.links.manage.index'))
            ->with('danger', 'File Link Deleted');
    }
}
