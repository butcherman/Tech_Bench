<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CustomerSite extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $primaryKey = 'cust_site_id';

    protected $guarded = ['updated_at', 'created_at', 'deleted_at'];

    protected $hidden = ['updated_at', 'created_at', 'deleted_at'];

    protected $casts = [
        'created_at' => 'datetime:M d, Y',
        'updated_at' => 'datetime:M d, Y',
        'deleted_at' => 'datetime:M d, Y',
    ];
}
