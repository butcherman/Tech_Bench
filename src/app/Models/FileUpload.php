<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class FileUpload extends Model
{
    use HasFactory;

    /** @var string */
    protected $primaryKey = 'file_id';

    /** @var array<int, string> */
    protected $guarded = ['file_id', 'created_at', 'updated_at'];

    /** @var array<int, string> */
    protected $hidden = ['disk', 'created_at', 'folder', 'updated_at', 'public'];

    /** @var array<int, string> */
    protected $appends = ['href', 'created_stamp'];

    /*
    |---------------------------------------------------------------------------
    | Model Casting
    |---------------------------------------------------------------------------
    */
    protected function casts(): array
    {
        return [
            'created_at' => 'datetime:M d, Y',
            'updated_at' => 'datetime:M d, Y',
        ];
    }

    /*
    |---------------------------------------------------------------------------
    | Model Attributes
    |---------------------------------------------------------------------------
    */
    public function href(): Attribute
    {
        return Attribute::make(
            get: fn () => route('download', [$this->file_id, $this->file_name]),
        );
    }

    public function createdStamp(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->created_at,
        );
    }

    /*
    |---------------------------------------------------------------------------
    | Additional Methods
    |---------------------------------------------------------------------------
    */

    /**
     * Verify that the file exists in the storage system.
     */
    public function fileExists(): bool
    {
        return Storage::disk($this->disk)
            ->exists($this->folder.DIRECTORY_SEPARATOR.$this->file_name);
    }

    /**
     * Return the full path of the file
     */
    // public function getFilePath(): string
    // {
    //     return Storage::disk($this->disk)
    //         ->path($this->folder.DIRECTORY_SEPARATOR.$this->file_name);
    // }
}
