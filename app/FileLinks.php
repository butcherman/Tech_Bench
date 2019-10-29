<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FileLinks extends Model
{
    protected $primaryKey = 'link_id';
    protected $fillable = ['user_id', 'cust_id', 'link_hash', 'link_name', 'note', 'expire', 'allow_upload'];
    protected $casts = [
        'created_at' => 'datetime:M d, Y',
        'updated_at' => 'datetime:M d, Y',
        'expire'     => 'datetime:M d, Y'
    ];

    public function getAllowUploadAttribute()
    {
        return $this->attributes['allow_upload'] ? 'Yes' : 'No';
    }

    public function users()
    {
        return $this->belongsTo('App\Users', 'user_id', 'user_id');
    }

    public function fileLinkFiles()
    {
        return $this->belongsTo('App\FileLinkFiles', 'link_id', 'link_id');
    }

    public function fileLinkInstructions()
    {
        return $this->hasMany('App\FileLinkInstructions', 'link_id', 'link_id');
    }

    public function fileLinkNotes()
    {
        return $this->hasMany('App\FileLinkNotes', 'link_id', 'link_id');
    }

    public function customers()
    {
        return $this->belongsTo('App\Customers', 'cust_id', 'cust_id');
    }
}
