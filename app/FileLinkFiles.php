<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FileLinkFiles extends Model
{
    protected $primaryKey = 'link_file_id';
    protected $fillable = ['link_id', 'file_id', 'user_id', 'added_by', 'upload'];
    protected $casts = [
        'created_at' => 'datetime:M d, Y',
        'updated_at' => 'datetime:M d, Y'
    ];

    public function fileLinks()
    {
        return $this->belongsTo('App\FileLinks', 'link_id', 'link_id');
    }

    public function files()
    {
        return $this->hasOne('App\Files', 'file_id', 'file_id');
    }

    public function User()
    {
        return $this->hasMany('App\User', 'user_id', 'user_id');
    }
}
