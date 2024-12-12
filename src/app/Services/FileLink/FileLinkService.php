<?php

namespace App\Services\FileLink;

use App\Events\File\FileUploadDeletedEvent;
use App\Jobs\File\MoveTmpFilesJob;
use App\Models\FileLink;
use App\Models\FileLinkFile;
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
    public function createFileLink(
        Collection $requestData,
        User $user,
        array $fileList
    ): FileLink {
        $newLink = new FileLink($requestData->all());
        $newLink->link_hash = Str::uuid()->toString();

        // Attach link to user
        $user->FileLinks()->save($newLink);

        // Save any uploaded files
        $timeline = $this->createTimelineEntry(
            $newLink,
            $user->user_id
        );

        if (count($fileList)) {
            $this->processLinkFiles($newLink, $timeline, false, $fileList);
            MoveTmpFilesJob::dispatch($fileList, $newLink->link_id);
        }

        return $newLink;
    }

    /**
     * Update an existing File Link
     */
    public function updateFileLink(Collection $requestData, FileLink $link): FileLink
    {
        $link->update($requestData->all());

        return $link;
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
     * Destroy a File Link
     */
    public function destroyFileLink(FileLink $link): void
    {
        $link->delete();
    }

    /**
     * Add a file to a File Link
     */
    public function addFileLinkFile(
        FileLink $link,
        array $fileList,
        int|string $addedBy
    ): void {
        $timeline = $this->createTimelineEntry(
            $link,
            $addedBy
        );

        if (count($fileList)) {
            $this->processLinkFiles($link, $timeline, false, $fileList);
        }
    }

    /**
     * Remove a File from a File Link
     */
    public function removeFileLinkFile(FileLinkFile $linkFile): void
    {
        $fileId = $linkFile->file_id;
        $linkFile->delete();

        event(new FileUploadDeletedEvent($fileId));
    }

    /**
     * Attach any files associated with the link
     */
    protected function processLinkFiles(
        FileLink $link,
        FileLinkTimeline $timeline,
        bool $isUpload,
        array $fileList
    ): FileLinkTimeline {
        if ($fileList) {
            $link->FileUpload()->attach($fileList, [
                'timeline_id' => $timeline->timeline_id,
                'upload' => $isUpload,
            ]);
        }

        return $timeline;
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
