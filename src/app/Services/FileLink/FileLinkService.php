<?php

namespace App\Services\FileLink;

use App\Jobs\FileLink\ProcessLinkFilesJob;
use App\Models\FileLink;
use App\Models\FileLinkNote;
use App\Models\FileLinkTimeline;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class FileLinkService
{
    /**
     * Create a new File Link for User
     */
    public function createFileLink(Collection $requestData, User $user, array $fileList): FileLink
    {
        $newLink = new FileLink($requestData->all());
        // $newLink->link_hash = Str::uuid()->toString();

        // Attach link to user
        $user->FileLinks()->save($newLink);

        // Save any uploaded files
        $timeline = $this->createTimelineEntry($newLink, $user->user_id);
        $timeline->Notes()->save(new FileLinkNote([
            'note' => 'File Link Created',
        ]));

        if (count($fileList)) {
            $newLink->Files()->attach($fileList, [
                'timeline_id' => $timeline->timeline_id,
                'upload' => false,
            ]);
            ProcessLinkFilesJob::dispatch($newLink);
        }

        return $newLink;
    }

    /**
     * Update an existing File Link
     */
    public function updateFileLink(Collection $requestData, FileLink $link): FileLink
    {
        $link->update($requestData->all());

        return $link->fresh();
    }

    /**
     * Destroy a File Link
     */
    public function destroyFileLink(FileLink $link): void
    {
        $link->delete();
    }

    /**
     * Extend the Expiration Date of a File Link.
     */
    public function extendFileLink(FileLink $link): void
    {
        $link->update([
            'expire' => $link->expire->addDays(30),
        ]);
    }

    /**
     * Expire a File Link - set expiration to yesterday.
     */
    public function expireFileLink(FileLink $link): void
    {
        $link->update([
            'expire' => Carbon::yesterday(),
        ]);
    }

    /**
     * Create a Timeline Entry to link files and notes to.
     */
    protected function createTimelineEntry(FileLink $link, string|int $addedBy): FileLinkTimeline
    {
        $timeline = new FileLinkTimeline([
            'added_by' => $addedBy,
        ]);

        $link->Timeline()->save($timeline);

        return $timeline;
    }
}
