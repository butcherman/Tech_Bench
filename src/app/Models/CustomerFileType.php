<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerFileType extends Model
{
    /** @var array<int, string> */
    protected $primaryKey = 'file_type_id';

    /** @var array<int, string> */
    protected $guarded = ['file_type_id', 'created_at', 'updated_at'];

    /** @var array<int, string> */
    protected $hidden = ['created_at', 'updated_at'];
}
