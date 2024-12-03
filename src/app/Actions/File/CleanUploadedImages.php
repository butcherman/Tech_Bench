<?php

namespace App\Actions\File;

use App\Models\CustomerNote;
use App\Models\TechTip;
use DOMDocument;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class CleanUploadedImages
{
    /**
     * If we should automatically remove dangling images.
     *
     * @var bool
     */
    protected $fix;

    /**
     * Storage Facade
     *
     * @var \Illuminate\Support\Facades\Storage
     */
    protected $storage;

    /*
    |---------------------------------------------------------------------------
    | Clean up any image files that do not have a pointer somewhere in the
    | database.  Possible pointers are:
    |   - Logo Files
    |   - Tech Tip's
    |   - Customer notes
    |   - File Link Notes
    |---------------------------------------------------------------------------
    */
    public function __invoke(bool $fix): array
    {
        $this->fix = $fix;
        $this->storage = Storage::disk('public');

        Log::info('Checking for unused images');

        $logoFiles = $this->cleanLogoFolder();
        $uploadFiles = $this->cleanUploadedFolder();

        Log::info('Search for unused images completed', [
            'found_logo_files' => $logoFiles,
            'found_upload_files' => $uploadFiles,
        ]);

        $mergedArr = array_merge($logoFiles, $uploadFiles);

        if ($this->fix) {
            $this->deleteUnusedFiles($mergedArr);

            Log::info('Deleted '.count($mergedArr).' unused image files');
        }

        return [
            'logo_files' => count($logoFiles),
            'upload_files' => count($uploadFiles),
            'total_files' => count($mergedArr),
            'deleted' => $this->fix,
        ];
    }

    /**
     * Get all images from the public logo files except for the current logo.
     */
    protected function cleanLogoFolder(): array
    {
        $currentLogo = [config('app.logo')];
        $logoFiles = $this->storage->files('images/logo');

        return $this->clearInUseItems($logoFiles, $currentLogo);
    }

    /**
     * Get all images from the public/images/uploaded directory and clear out
     * the ones that are still in use.
     */
    protected function cleanUploadedFolder(): array
    {
        $fileList = $this->storage->allFiles('images/uploaded');
        $usedList = $this->getImageTags();

        return $this->clearInUseItems($fileList, $usedList);
    }

    /**
     * Delete unused files.
     */
    protected function deleteUnusedFiles(array $fileArr): void
    {
        foreach ($fileArr as $file) {
            $this->storage->delete($file);

            Log::info('Deleted File - '.$file);
        }
    }

    /**
     * Go through found items and remove those still in-use
     */
    protected function clearInUseItems(array $foundFiles, array $inUseFiles): array
    {
        $filenameArr = $this->getFilenameArray($inUseFiles);

        return Arr::where($foundFiles, function ($foundFile) use ($filenameArr) {
            $fileInfo = pathinfo($foundFile);

            if (! in_array($fileInfo['filename'], $filenameArr)) {
                return $foundFile;
            }
        });
    }

    protected function getFilenameArray(array $fileList): array
    {
        return Arr::map($fileList, function ($file) {
            $fileInfo = pathinfo($file);

            return $fileInfo['filename'];
        });
    }

    /**
     * Find any image tags in identified database columns
     */
    protected function getImageTags(): array
    {
        $document = new DOMDocument;
        $modelList = [
            CustomerNote::class => 'details',
            TechTip::class => 'details',
            // FileLink::class => 'instructions',
            // FileLinkNote::class => 'note',
        ];

        $foundImages = [];

        // Go through each of the models, and find img tags
        foreach ($modelList as $model => $column) {
            $tableRows = $model::select($column)->get();

            foreach ($tableRows as $row) {
                if ($row[$column]) {
                    @$document->loadHTML($row[$column]);
                    $tagList = $document->getElementsByTagName('img');

                    foreach ($tagList as $tag) {
                        // If the entire image is stored in the database, skip
                        if (! preg_match('/^data:image/', $tag->getAttribute('src'))) {
                            $img = $tag->getAttribute('src');
                            $imgProperties = pathinfo($img);
                            $foundImages[] = $imgProperties['filename'];
                        }
                    }
                }
            }
        }

        return $foundImages;
    }
}
