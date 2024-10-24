<?php

namespace App\Service\FileLink;

use App\Models\FileLink;
use App\Models\FileLinkTimeline;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class FileLinkService
{
    public function createFileLink(Collection $requestData): FileLink
    {
        $newLink = new FileLink($requestData->all());
        $newLink->link_hash = Str::uuid()->toString();

        // Attach link to user
        $currentUser = request()->user();
        $currentUser->FileLink()->save($newLink);

        // Save any uploaded files
        $timeline = $this->createTimelineEntry($newLink, $currentUser->user_id);
        $this->processLinkFiles($newLink, $timeline, false);

        return $newLink;
    }

    public function updateFileLink(Collection $requestData, FileLink $link): FileLink
    {
        $link->update($requestData->all());

        return $link;
    }

    public function destroyFileLink(FileLink $link): void
    {
        $link->delete();
    }

    protected function processLinkFiles(
        FileLink $link,
        FileLinkTimeline $timeline,
        bool $isUpload
    ): FileLinkTimeline {
        $fileList = session()->pull('link-file');
        if ($fileList) {
            $link->FileUpload()->attach($fileList, [
                'timeline_id' => $timeline->timeline_id,
                'upload' => $isUpload,
            ]);
        }

        return $timeline;
    }

    protected function createTimelineEntry(FileLink $link, string|int $addedBy): FileLinkTimeline
    {
        $timeline = new FileLinkTimeline([
            'added_by' => $addedBy,
        ]);

        $link->Timeline()->save($timeline);

        return $timeline;
    }
}
