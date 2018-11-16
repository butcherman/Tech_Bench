<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FileLinks extends Model
{
    protected $primaryKey = 'link_id';
    protected $fillable = ['user_id', 'link_hash', 'link_name', 'expire', 'allow_upload'];
    
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
}
