<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TechTipFile extends Model
{
    use HasFactory;

    protected $primaryKey = 'tip_file_id';

    protected $guarded = ['tip_file_id', 'created_at', 'updated_at'];

    protected $hidden = [];

    /**
     * Each Tech Tip file is attached to a file upload id
     */
    public function FileUpload()
    {
        return $this->hasOne(FileUploads::class, 'file_id', 'file_id');
    }
}
