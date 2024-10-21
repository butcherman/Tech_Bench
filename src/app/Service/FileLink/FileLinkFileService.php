<?php

namespace App\Service\FileLink;

use App\Events\File\FileDataDeletedEvent;
use App\Events\FileLinks\FileUploadedFromPrivateEvent;
use App\Models\FileLink;
use App\Models\FileLinkFile;
use App\Traits\FileTrait;
use Illuminate\Http\Request;

class FileLinkFileService extends FileLinkService
{
    use FileTrait;

    public function processIncomingFile(
        Request $requestData,
        ?FileLink $link = null,
        ?bool $isPublic = false
    ): void {
        $saveLocation = $link ? $link->link_id : 'tmp';

        $this->setFileData('fileLinks', $saveLocation, $isPublic);

        if ($savedFile = $this->getChunk($requestData)) {
            if (is_null($link)) {
                session()->push('link-file', $savedFile->file_id);

                return;
            }

            $timeline = $this->createTimelineEntry($link, $requestData->user());

            $link->FileUpload()->attach($savedFile, [
                'timeline_id' => $timeline->timeline_id,
            ]);

            event(new FileUploadedFromPrivateEvent($link));
        }
    }

    public function destroyLinkFile(FileLinkFile $linkFile): void
    {
        $linkFile->delete();

        event(new FileDataDeletedEvent($linkFile->file_id));
    }
}
