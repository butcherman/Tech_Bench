<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FileLinkInstructions extends Model
{
    protected $primaryKey = 'link_instructions_id';
    protected $fillable = ['link_id', 'instructions'];
    
    public function fileLinks()
    {
        return $this->belongsTo('App\FileLinks', 'link_id');
    }
}
