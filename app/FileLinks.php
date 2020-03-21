<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FileLinks extends Model
{
    protected $primaryKey = 'link_id';
    protected $fillable   = ['user_id', 'cust_id', 'link_hash', 'link_name', 'note', 'expire', 'allow_upload'];
    protected $casts      = [
        'created_at' => 'datetime:M d, Y',
        'updated_at' => 'datetime:M d, Y',
        'expire'     => 'datetime:M d, Y'
    ];

    public function getAllowUploadAttribute()
    {
        return $this->attributes['allow_upload'] ? 'Yes' : 'No';
    }

    public function fileLinkFiles()
    {
        return $this->hasMany('App\FileLinkFiles', 'link_id', 'link_id');
    }
}
