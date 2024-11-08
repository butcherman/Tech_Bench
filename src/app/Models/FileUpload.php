<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FileUpload extends Model
{
    /** @var string */
    protected $primaryKey = 'file_id';

    /** @var array<int, string> */
    protected $guarded = ['file_id', 'created_at', 'updated_at'];

    /** @var array<int, string> */
    protected $hidden = ['disk', 'created_at', 'folder', 'updated_at', 'public'];

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
}
