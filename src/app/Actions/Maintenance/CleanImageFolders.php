<?php

namespace App\Actions\Maintenance;

use App\Models\CustomerNote;
use App\Models\FileLink;
use App\Models\FileLinkNote;
use App\Models\TechTip;
use DOMDocument;
use Illuminate\Support\Facades\Storage;

class CleanImageFolders
{
    /**
     * Storage object where images are stored.
     *
     * @var Storage
     */
    protected $storage;

    /**
     * List of Database Tables that can contain image files.
     *
     * @var array<string, string>
     */
    protected $modelList = [
        CustomerNote::class => 'details',
        FileLink::class => 'instructions',
        FileLinkNote::class => 'note',
        TechTip::class => 'details',
    ];

    /**
     * Remove any unused images loaded into the public folders.
     */
    public function __invoke(bool $fix = false): int
    {
        $this->storage = Storage::disk('public');

        $logoFiles = $this->checkLogoFolder();
        $uploadedFiles = $this->checkUploadFolder();

        $fullList = array_merge($logoFiles, $uploadedFiles);

        if ($fix) {
            $this->removeFiles($fullList);
        }

        return count($fullList);
    }

    /**
     * Get all images from the logo folder, except the current logo.
     */
    protected function checkLogoFolder(): array
    {
        $fileList = $this->storage->files('images/logo');

        $currentLogo = str_replace('/storage/', '', config('app.logo'));
        $logoKey = array_search($currentLogo, $fileList);

        // Remove the current logo file.
        if ($logoKey !== false) {
            unset($fileList[$logoKey]);
        }

        return $fileList;
    }

    /**
     * Get a list of unused images from the upload folder.
     */
    protected function checkUploadFolder(): array
    {
        $fileList = $this->storage->allFiles('images/uploaded');
        $imageList = $this->getImageTags();
        $orphanedList = [];

        // Compare the file list and image list
        foreach ($fileList as $imgFile) {
            $imgData = pathinfo($imgFile);

            if (! in_array($imgData['filename'], $imageList)) {
                $orphanedList[] = $imgFile;
            }
        }

        return $orphanedList;
    }

    /**
     * Find any image tags in identified database columns
     */
    protected function getImageTags(): array
    {
        $document = new DOMDocument;
        $foundImages = [];

        // Go through each of the models, and find img tags
        foreach ($this->modelList as $model => $column) {
            $tableRows = $model::select($column)->get();

            foreach ($tableRows as $row) {
                if ($row[$column]) {
                    @$document->loadHTML($row[$column]);
                    $tagList = $document->getElementsByTagName('img');

                    foreach ($tagList as $tag) {
                        // If the entire image is stored in the database, we will not parse it
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

    /**
     * Remove all of the necessary files
     */
    protected function removeFiles(array $fileList): void
    {
        foreach ($fileList as $fileData) {
            $this->storage->delete($fileData);
        }
    }
}
