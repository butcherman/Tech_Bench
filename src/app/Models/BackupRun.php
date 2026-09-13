<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BackupRun extends Model
{
    /** @var array<int, string> */
    protected $guarded = ['backup_id', 'created_at', 'updated_at'];

    /** @var string */
    protected $primaryKey = 'backup_id';
}
