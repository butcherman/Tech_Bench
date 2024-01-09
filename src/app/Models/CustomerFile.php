<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CustomerFile extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $primaryKey = 'cust_file_id';

    protected $guarded = ['cust_file_id', 'created_at', 'updated_at'];

    protected $hidden = [
        'cust_id',
        'updated_at',
        'user_id',
        'deleted_at',
        'CustomerEquipment',
    ];

    protected $casts = [
        'created_at' => 'datetime:M d, Y',
        'updated_at' => 'datetime:M d, Y',
        'deleted_at' => 'datetime:M d, Y',
        'shared' => 'boolean',
    ];
}
