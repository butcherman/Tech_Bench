<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CustomerFile extends Model
{
    use SoftDeletes;

    /** @var string */
    protected $primaryKey = 'cust_file_id';

    /** @var array<int, string> */
    protected $guarded = ['cust_file_id', 'created_at', 'updated_at'];

    /** @var array<int, string> */
    protected $hidden = [
        'cust_id',
        'updated_at',
        'user_id',
        'deleted_at',
        'CustomerFileType',
        'CustomerEquipment',
        'user',
    ];
}
