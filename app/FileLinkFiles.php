<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FileLinkFiles extends Model
{
    protected $primaryKey = 'link_file_id';
    protected $fillable   = ['link_id', 'file_id', 'user_id', 'added_by', 'upload', 'note'];
    protected $casts      = [
        'created_at' => 'datetime:M d, Y',
        'updated_at' => 'datetime:M d, Y'
    ];

    public function files()
    {
        return $this->hasOne('App\Files', 'file_id', 'file_id');
    }

    public function User()
    {
        return $this->hasOne('App\User', 'user_id', 'user_id');
    }
}
