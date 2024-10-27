<?php

namespace App\Models;

use App\Observers\UserVerificationCodeObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Model;

#[ObservedBy([UserVerificationCodeObserver::class])]
class UserVerificationCode extends Model
{
    /** @var string */
    protected $primaryKey = 'id';

    /** @var array<int, string> */
    public $guarded = ['id', 'created_at', 'updated_at'];

    /*
    |---------------------------------------------------------------------------
    | Model Relationships
    |---------------------------------------------------------------------------
    */
    public function User()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }
}
