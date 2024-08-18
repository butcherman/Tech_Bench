<?php

namespace App\Http\Controllers\FileLink;

use App\Http\Controllers\Controller;
use App\Http\Resources\FileLinkAdminResource;
use App\Http\Resources\FileLinkTableResource;
use App\Models\FileLink;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class FileLinkAdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('FileLinks/Manage/Index', [
            'link-list' => FileLinkAdminResource::collection(FileLink::all()),
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(FileLink $link)
    {
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
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, FileLink $link)
    {
        $this->authorize('delete', $link);

        $link->delete();

        Log::info(
            'File Link deleted by ' . $request->user()->username,
            $link->toArray()
        );

        return redirect(route('admin.links.manage.index'))
            ->with('danger', 'File Link Deleted');
    }
}
