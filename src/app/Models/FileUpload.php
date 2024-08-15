<?php

namespace App\Models;

use App\Exceptions\Filesystem\FileMissingException;
use App\Exceptions\Filesystem\IncorrectFilenameException;
use App\Exceptions\Filesystem\PrivateFileException;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class FileUpload extends Model
{
    use HasFactory;

    protected $primaryKey = 'file_id';

    protected $guarded = ['file_id', 'created_at', 'updated_at'];

    protected $hidden = ['disk', 'created_at', 'folder', 'updated_at', 'public'];

    protected $appends = ['href', 'created_stamp'];

    protected $casts = [
        'created_at' => 'datetime:M d, Y',
        'updated_at' => 'datetime:M d, Y',
    ];

    public function getHrefAttribute()
    {
        return route('download', [$this->file_id, $this->file_name]);
    }

    public function getCreatedStampAttribute()
    {
        return $this->created_at;
    }

    public function validateFile($fileName)
    {
        $this->verifyFileName($fileName);
        $this->verifyPublicDownload();
        $this->verifyFileExists();
    }

    /**
     * Verify that the File Name passed to download route matches file
     */
    protected function verifyFileName($fileName)
    {
        if ($fileName !== $this->file_name) {
            throw new IncorrectFilenameException($fileName, $this);
        }
    }

    /**
     * If the file is public, make sure the file is tagged for public download
     */
    protected function verifyPublicDownload()
    {
        if (!Auth::check() && !$this->public) {
            throw new PrivateFileException($this);
        }
    }

    /**
     * Verify that the file exists on the file system
     */
    protected function verifyFileExists()
    {
        if (
            Storage::disk($this->disk)
                ->missing($this->folder . DIRECTORY_SEPARATOR . $this->file_name)
        ) {
            throw new FileMissingException($this);
        }
    }

    /**
     * Return the full path of the file
     */
    public function getFilePath()
    {
        return Storage::disk($this->disk)
            ->path($this->folder . DIRECTORY_SEPARATOR . $this->file_name);
    }
}
