<?php

namespace App\Http\Controllers\FileLink;

use App\Events\File\FileDataDeletedEvent;
use App\Http\Controllers\Controller;
use App\Models\FileLink;
use App\Models\FileLinkFile;
use App\Models\FileLinkTimeline;
use App\Traits\FileTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class FileLinkFileController extends Controller
{
    use FileTrait;

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, FileLink $link)
    {
        $this->authorize('update', $link);

        $this->setFileData('fileLinks', $link->link_id, true);

        if ($request->file) {
            if ($savedFile = $this->getChunk($request)) {
                Log::debug('File Link file saved', $savedFile->toArray());
            }

            $timeline = FileLinkTimeline::create([
                'added_by' => $request->user()->user_id
            ]);
            $link->FileUpload()->attach($savedFile, [
                'timeline_id' => $timeline->timeline_id,
            ]);
        }

        return response()->noContent();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, FileLink $link, FileLinkFile $linkFile)
    {
        $this->authorize('update', $link);

        $linkFile->delete();

        Log::info('File for File Link deleted by ' . $request->user()->username, [
            'link-data' => $link->toArray(),
            'file-data' => $linkFile->toArray(),
        ]);
        event(new FileDataDeletedEvent($linkFile->file_id));

        return back()->with('warning', 'File Deleted');
    }
}
