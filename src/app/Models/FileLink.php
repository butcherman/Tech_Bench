<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FileLink extends Model
{
    use HasFactory;

    protected $primaryKey = 'link_id';

    protected $guarded = ['link_id', 'created_at', 'updated_at'];

    protected $casts = [
        'allow_upload' => 'boolean',
        'created_at' => 'datetime:M d, Y',
        'updated_at' => 'datetime:M d, Y',
    ];

    public function FileUpload()
    {
        return $this->belongsToMany(
            FileUpload::class,
            'file_link_files',
            'link_id',
            'file_id',
        );
    }
}
