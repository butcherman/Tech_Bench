<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FileLinkNotes extends Model
{
    protected $primaryKey = 'link_note_id';
    protected $fillable = ['link_id', 'file_id', 'note'];
    
    public function fileLinks()
    {
        return $this->belongsTo('App\FileLinks', 'link_id', 'link_id');
    }
    
    public function files()
    {
        return $this->belongsTo('App\Files', 'file_id', 'file_id');
    }
}
