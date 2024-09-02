<?php

namespace App\Service\Maint;

use App\Models\CustomerNote;
use App\Models\FileLink;
use App\Models\FileLinkNote;
use App\Models\TechTip;
use DOMDocument;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ImageFileCleanupService
{
    protected $storage;

    protected $logoFiles;

    protected $uploadedFiles;

    public function __construct(protected bool $readOnly = false)
    {
        $this->storage = Storage::disk('public');
    }

    public function handle()
    {
        $this->logoFiles = $this->cleanLogoFolder();
        $this->uploadedFiles = $this->cleanUploadedFolder();

        if (! $this->readOnly) {
            $this->removeFiles($this->logoFiles);
            $this->removeFiles($this->uploadedFiles);
        }
    }

    public function getFileCount()
    {
        return count(array_merge($this->logoFiles, $this->uploadedFiles));
    }

    public function getFileList()
    {
        return array_merge($this->logoFiles, $this->uploadedFiles);
    }

    /**
     * Get all images from the public logo files, except for current logo
     */
    protected function cleanLogoFolder()
    {
        $fileList = $this->storage->files('images/logo');
        $fullLogo = pathinfo(config('app.logo'));
        $currentLogo = $fullLogo['filename'];
        $clearList = [];

        Log::debug('File Image List from Logo Folder', $fileList);
        Log::debug('Current Logo', $fullLogo);

        foreach ($fileList as $logoFile) {
            $logoData = pathinfo($logoFile);
            Log::debug('File Data from Logo Folder', $logoData);

            if ($logoData['filename'] !== $currentLogo) {
                Log::debug('Unused Logo File Found -'.$logoFile);
                $clearList[] = $logoFile;
            }
        }

        return $clearList;
    }

    /**
     * Get all images from the public Uploaded folder, except those in use
     */
    protected function cleanUploadedFolder()
    {
        $fileList = $this->storage->allFiles('images/uploaded');
        $foundList = $this->getImageTags();
        $clearList = [];

        Log::debug('File List from Uploads Folder', $fileList);
        Log::debug('List of image files in use', $foundList);

        foreach ($fileList as $imgFile) {
            $imgData = pathinfo($imgFile);

            if (! in_array($imgData['filename'], $foundList)) {
                Log::debug('Unused Uploaded File found - '.$imgFile);
                $clearList[] = $imgFile;
            }
        }

        return $clearList;
    }

    /**
     * Remove all of the necessary files
     */
    protected function removeFiles(array $fileList)
    {
        foreach ($fileList as $fileData) {
            Log::debug('Deleting File - '.$fileData);
            $this->storage->delete($fileData);
        }

        Log::info('Deleted '.count($fileList).' files from Public Disk', $fileList);
    }

    /**
     * Find any image tags in identified database columns
     */
    protected function getImageTags()
    {
        $document = new DOMDocument;
        $modelList = [
            CustomerNote::class => 'details',
            FileLink::class => 'instructions',
            FileLinkNote::class => 'note',
            TechTip::class => 'details',
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
}
