<?php

namespace App\Models;

use App\Observers\DeviceTokenObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

#[ObservedBy([DeviceTokenObserver::class])]
class DeviceToken extends Model
{
    use HasFactory;

    /** @var string */
    protected $primaryKey = 'device_id';

    /** @var array<int, string> */
    protected $guarded = ['device_id', 'created_at', 'updated_at'];

    /** @var array<int, string> */
    protected $hidden = ['user_id', 'token'];

    /** @var array<string, string> */
    protected $casts = [
        'created_at' => 'datetime:M d, Y',
        'updated_at' => 'datetime:M d, Y',
    ];

    /*
    |---------------------------------------------------------------------------
    | Model Relationships
    |---------------------------------------------------------------------------
    */
    public function User(): HasOne
    {
        return $this->hasOne(User::class, 'user_id', 'user_id');
    }
}
