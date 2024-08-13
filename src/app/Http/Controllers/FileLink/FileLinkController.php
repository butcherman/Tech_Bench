<?php

namespace App\Http\Controllers\FileLink;

use App\Enum\CrudAction;
use App\Events\FileLinks\FileLinkEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\FileLink\FileLinkRequest;
use App\Http\Resources\FileLinkTableResource;
use App\Models\FileLink;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class FileLinkController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', FileLink::class);

        return Inertia::render('FileLinks/Index', [
            'link-list' => $request->user()->FileLink,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('FileLinks/Create', [
            'default-expire' => Carbon::now()
                ->addDays(config('fileLink.default_link_life'))
                ->format('Y-m-d'),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(FileLinkRequest $request)
    {
        $newLink = $request->createFileLink();

        event(new FileLinkEvent($newLink, CrudAction::Create));
        Log::info(
            'New File Link created by ' . $request->user()->username,
            $newLink->toArray()
        );

        return redirect(route('links.show', $newLink->link_id))
            ->with('success', 'File Link Created');
    }

    /**
     * Display the specified resource.
     */
    public function show(FileLink $link)
    {
        return Inertia::render('FileLinks/Show', [
            'link' => $link,
            'table-data' => FileLinkTableResource::make($link),
            'link-files' => $link->FileUpload,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        return Inertia::render('FileLinks/Edit');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        return 'update';
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


        return redirect(route('links.index'))->with('danger', 'File Link Deleted');
    }
}
