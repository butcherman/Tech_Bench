<?php

namespace App\Http\Controllers\Public;

use App\Events\FileLinks\FileUploadedFromPublicEvent;
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
        $this->validatePublicLink($request, $link);

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
     * Upload a public file to the File Link
     */
    public function update(PublicFileLinkRequest $request, FileLink $link)
    {
        $this->validatePublicLink($request, $link);
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

        $timeline = FileLinkTimeline::create([
            'link_id' => $link->link_id,
            'added_by' => $request->name,
        ]);

        // Once file is loaded, update other information
        if ($request->session()->has('link-file')) {
            $fileList = $request->session()->pull('link-file');
            $link->FileUpload()->attach($fileList, [
                'timeline_id' => $timeline->timeline_id,
                'upload' => true,
            ]);
        }

        if ($request->notes) {
            FileLinkNote::create([
                'timeline_id' => $timeline->timeline_id,
                'note' => $request->notes,
            ]);
        }

        event(new FileUploadedFromPublicEvent($link, $timeline));

        return back()->with('success', 'Files Uploaded Successfully');
    }

    /**
     * Verify that the link is valid
     */
    protected function validatePublicLink(Request $request, FileLink $fileLink)
    {
        if (Carbon::parse($fileLink->expire) < Carbon::now()) {
            throw new FileLinkExpiredException($request, $fileLink);
        }
    }
}
