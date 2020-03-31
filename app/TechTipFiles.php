<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TechTipFiles extends Model
{
    protected $primaryKey = 'tip_file_id';
    protected $fillable   = ['tip_id', 'file_id'];
    protected $hidden     = ['created_at', 'updated_at'];

    public function files()
    {
        return $this->hasOne('App\Files', 'file_id', 'file_id');
    }
}
