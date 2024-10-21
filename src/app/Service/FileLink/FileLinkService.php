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
        $currentUser = request()->user();

        $newLink = new FileLink($requestData->all());
        $newLink->link_hash = Str::uuid()->toString();

        // Attach link to user
        $currentUser->FileLink()->save($newLink);

        $this->processLinkFiles($newLink, $currentUser);

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

    protected function processLinkFiles(FileLink $link, User $currentUser): void
    {
        $fileList = session()->pull('link-file');
        if ($fileList) {
            $timeline = $this->createTimelineEntry($link, $currentUser);
            $link->FileUpload()->syncWithPivotValues($fileList, [
                'timeline_id' => $timeline->timeline_id,
            ]);
        }
    }

    protected function createTimelineEntry(FileLink $link, User $user): FileLinkTimeline
    {
        $timeline = new FileLinkTimeline([
            'added_by' => $user->user_id,
        ]);

        $link->Timeline()->save($timeline);

        return $timeline;
    }
}
