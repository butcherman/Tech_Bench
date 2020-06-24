<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Files extends Model
{
    protected $primaryKey = 'file_id';
    protected $fillable   = ['file_name', 'file_link', 'public'];
    protected $hidden     = ['created_at', 'updated_at', 'file_link', 'public'];

    public function CustomerFiles()
    {
        return $this->belongsTo('App\CustomerFiles', 'file_id', 'file_id');
    }

    public function FileLinkFiles()
    {
        return $this->belongsTo('App\FileLinkFiles', 'file_id', 'file_id');
    }

    public function TechTipFiles()
    {
        return $this->belongsTo('App\TechTipFiles', 'file_id', 'file_id');
    }
}
