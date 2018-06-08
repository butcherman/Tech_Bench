<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FileLinkFiles extends Model
{
    protected $primaryKey = 'link_file_id';
    protected $fillable = ['link_id', 'file_id', 'user_id', 'added_by', 'upload'];
    
    public function fileLinks()
    {
        return $this->hasMany('App\FileLinks', 'link_id', 'link_id');
    }
    
    public function files()
    {
        return $this->belongsTo('App\Files', 'file_id', 'file_id');
    }
}
