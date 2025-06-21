<?php

namespace App\Models;

use App\Observers\TechTipTypeObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[ObservedBy([TechTipTypeObserver::class])]
class TechTipType extends Model
{
    use HasFactory;

    /** @var string */
    protected $primaryKey = 'tip_type_id';

    /** @var array<int, string> */
    protected $guarded = ['tip_type_id', 'created_at', 'updated_at'];

    /** @var array<int, string> */
    protected $hidden = ['created_at', 'updated_at'];
}
