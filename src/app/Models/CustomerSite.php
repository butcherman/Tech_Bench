<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerSite extends Model
{
    /** @var string */
    protected $primaryKey = 'cust_site_id';

    /** @var array<int, string> */
    protected $guarded = ['updated_at', 'created_at', 'deleted_at'];

    /** @var array<int, string> */
    protected $hidden = [
        'updated_at',
        'created_at',
        'deleted_at',
        'deleted_reason',
        'Customer',
        'href',
        'pivot',
    ];

    /** @var array<string, string> */
    protected $casts = [
        'created_at' => 'datetime:M d, Y',
        'updated_at' => 'datetime:M d, Y',
        'deleted_at' => 'datetime:M d, Y',
    ];
}
