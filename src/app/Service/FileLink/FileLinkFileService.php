<?php

namespace App\Service\FileLink;

use App\Events\File\FileDataDeletedEvent;
use App\Events\FileLinks\FileUploadedFromPublicEvent;
use App\Models\FileLink;
use App\Models\FileLinkFile;
use App\Models\FileLinkNote;
use App\Traits\FileTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class FileLinkFileService extends FileLinkService
{
    use FileTrait;

    /**
     * Temporary Storage location for file uploads when no link available
     *
     * @var string
     */
    protected $tmpFolder = 'tmp';

    public function processIncomingFile(
        Request $requestData,
        ?FileLink $link = null,
        ?bool $isPublic = false
    ): void {
        $saveLocation = $link ? $link->link_id : $this->tmpFolder;

        $this->setFileData('fileLinks', $saveLocation, $isPublic);

        if ($savedFile = $this->getChunk($requestData)) {
            session()->push('link-file', $savedFile->file_id);

        }
    }

    public function savePrivateLoadedFile(FileLink $link): void
    {
        $timeline = $this->createTimelineEntry($link, request()->user()->user_id);
        $this->processLinkFiles($link, $timeline, false);
    }

    public function savePublicLoadedFile(Collection $requestData, FileLink $link): void
    {
        $timeline = $this->createTimelineEntry($link, $requestData->get('name'));
        $this->processLinkFiles($link, $timeline, true);

        $note = $requestData->get('notes');
        if ($note) {
            FileLinkNote::create([
                'timeline_id' => $timeline->timeline_id,
                'note' => $requestData->get('notes'),
            ]);
        }

        event(new FileUploadedFromPublicEvent($link, $timeline));
    }

    public function destroyLinkFile(FileLinkFile $linkFile): void
    {
        $linkFile->delete();

        event(new FileDataDeletedEvent($linkFile->file_id));
    }

    public function moveTmpFiles(FileLink $link): void
    {
        $fileList = $link->FileUpload;
        foreach ($fileList as $upload) {
            if ($upload->folder === $this->tmpFolder) {
                $this->moveFileUpload($upload, $link->link_id);
            }
        }
    }
}
