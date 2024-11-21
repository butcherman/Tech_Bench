<?php

namespace App\Models;

use App\Observers\UserVerificationCodeObserver;
use App\Traits\Models\HasUser;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Model;

#[ObservedBy([UserVerificationCodeObserver::class])]
class UserVerificationCode extends Model
{
    use HasUser;

    /** @var string */
    protected $primaryKey = 'id';

    /** @var array<int, string> */
    public $guarded = ['id', 'created_at', 'updated_at'];
}
