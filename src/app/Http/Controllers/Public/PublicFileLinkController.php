<?php

namespace App\Http\Controllers\Public;

use App\Exceptions\FileLink\FileLinkExpiredException;
use App\Http\Controllers\Controller;
use App\Http\Requests\FileLink\PublicFileLinkRequest;
use App\Models\FileLink;
use App\Models\FileLinkNote;
use App\Models\FileLinkTimeline;
use App\Traits\FileTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class PublicFileLinkController extends Controller
{
    use FileTrait;

    /**
     * Display the specified resource.
     */
    public function show(Request $request, FileLink $link)
    {
        // Make sure link is valid
        if (Carbon::parse($link->expire) < Carbon::now()) {
            throw new FileLinkExpiredException($request, $link);
        }

        return Inertia::render('Public/FileLinks/Show', [
            'link-data' => $link->only([
                'instructions',
                'allow_upload',
                'link_hash',
            ]),
            'link-files' => $link->FileUpload()
                ->wherePivot('upload', false)
                ->get()
                ->makeHidden('pivot'),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PublicFileLinkRequest $request, FileLink $link)
    {
        $this->setFileData('fileLinks', $link->link_id);

        if ($request->file) {
            if ($savedFile = $this->getChunk($request)) {
                Log::debug('File Link file saved', $savedFile->toArray());
                $request->session()->push('link-file', $savedFile->file_id);
                Log::debug(
                    'Current session file list',
                    $request->session()->get('link-file')
                );
            }

            return response()->noContent();
        }

        // Once file is loaded, update other information
        if ($request->session()->has('link-file')) {
            $fileList = $request->session()->pull('link-file');
            $timeline = FileLinkTimeline::create(['added_by' => $request->name]);
            $link->FileUpload()->attach($fileList, [
                'timeline_id' => $timeline->timeline_id,
            ]);
        }

        if ($request->notes) {
            FileLinkNote::create([
                'timeline_id' => $timeline->timeline_id,
                'note' => $request->notes,
            ]);
        }




        // todo - add flash to template
        return back()->with('success', 'Files Uploaded Successfully');
    }
}
