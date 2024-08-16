<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FileLinkNote extends Model
{
    use HasFactory;

    protected $primaryKey = 'link_note_id';

    protected $guarded = ['link_note_id', 'created_at', 'updated_at'];
}
