<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FileLinkNote extends Model
{
    /** @var string */
    protected $primaryKey = 'link_note_id';

    /** @var array<int, string> */
    protected $guarded = ['link_note_id', 'created_at', 'updated_at'];

    /** @var array<int, string> */
    protected $hidden = ['updated_at'];
}
