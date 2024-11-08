<?php

namespace App\Models;

use App\Observers\CustomerSiteObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

#[ObservedBy([CustomerSiteObserver::class])]
class CustomerSite extends Model
{
    use HasFactory;
    use SoftDeletes;

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

    /*
    |---------------------------------------------------------------------------
    | Model Casting
    |---------------------------------------------------------------------------
    */
    protected function casts(): array
    {
        return [
            'created_at' => 'datetime:M d, Y',
            'updated_at' => 'datetime:M d, Y',
            'deleted_at' => 'datetime:M d, Y',
        ];
    }
}
