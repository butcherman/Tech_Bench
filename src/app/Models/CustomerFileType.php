<?php

namespace App\Models;

use App\Observers\CustomerFileTypeObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[ObservedBy([CustomerFileTypeObserver::class])]
class CustomerFileType extends Model
{
    use HasFactory;

    /** @var array<int, string> */
    protected $primaryKey = 'file_type_id';

    /** @var array<int, string> */
    protected $guarded = ['file_type_id', 'created_at', 'updated_at'];

    /** @var array<int, string> */
    protected $hidden = ['created_at', 'updated_at'];
}
