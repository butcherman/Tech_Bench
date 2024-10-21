<?php

namespace App\Http\Controllers\FileLink;

use App\Http\Controllers\Controller;
use App\Http\Resources\FileLinkAdminResource;
use App\Http\Resources\FileLinkTableResource;
use App\Models\FileLink;
use App\Service\FileLink\FileLinkService;
use Inertia\Inertia;

class FileLinkAdminController extends Controller
{
    public function __construct(protected FileLinkService $svc) {}

    /**
     * Display a listing all File Links for all users.
     */
    public function index()
    {
        $this->authorize('manage', FileLink::class);

        return Inertia::render('FileLinks/Manage/Index', [
            'link-list' => FileLinkAdminResource::collection(FileLink::all()),
        ]);
    }

    /**
     * Display another users File Link.
     */
    public function show(FileLink $link)
    {
        $this->authorize('manage', FileLink::class);

        return Inertia::render('FileLinks/Manage/Show', [
            'link' => $link,
            'table-data' => FileLinkTableResource::make($link),
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
    public function destroy(FileLink $link)
    {
        $this->authorize('delete', $link);

        $this->svc->destroyFileLink($link);

        return redirect(route('admin.links.manage.index'))
            ->with('danger', 'File Link Deleted');
    }
}
