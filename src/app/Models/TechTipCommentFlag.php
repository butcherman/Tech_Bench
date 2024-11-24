<?php

namespace App\Models;

use App\Observers\TechTipCommentFlagObserver;
use App\Traits\Models\HasUser;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

#[ObservedBy([TechTipCommentFlagObserver::class])]
class TechTipCommentFlag extends Model
{
    use HasUser;

    /** @var array<int, string> */
    protected $guarded = ['id', 'updated_at', 'created_at'];

    /** @var array<int, string> */
    protected $appends = ['flagged_by'];

    /** @var array<int, string> */
    protected $hidden = ['User'];

    /*
    |---------------------------------------------------------------------------
    | Model Casting
    |---------------------------------------------------------------------------
    */
    protected function casts(): array
    {
        return [
            'created_at' => 'datetime:M d, Y',
        ];
    }

    /*
    |---------------------------------------------------------------------------
    | Model Attributes
    |---------------------------------------------------------------------------
    */
    public function flaggedBy(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->User->full_name,
        );
    }
}
