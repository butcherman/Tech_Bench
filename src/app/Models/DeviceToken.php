<?php

namespace App\Models;

use App\Observers\DeviceTokenObserver;
use App\Traits\Models\HasUser;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[ObservedBy([DeviceTokenObserver::class])]
class DeviceToken extends Model
{
    use HasFactory;
    use HasUser;

    /** @var string */
    protected $primaryKey = 'device_id';

    /** @var array<int, string> */
    protected $guarded = ['device_id', 'created_at', 'updated_at'];

    /** @var array<int, string> */
    protected $hidden = ['user_id', 'token'];

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
        ];
    }
}
