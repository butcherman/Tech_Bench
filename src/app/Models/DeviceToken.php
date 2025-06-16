<?php

namespace App\Models;

use App\Observers\DeviceTokenObserver;
use App\Traits\Models\HasUser;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Prunable;
use Illuminate\Support\Facades\Log;

#[ObservedBy([DeviceTokenObserver::class])]
class DeviceToken extends Model
{
    use HasFactory;
    use HasUser;
    use Prunable;

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

    /*
    |---------------------------------------------------------------------------
    | Prune devices after 180 days
    |---------------------------------------------------------------------------
    */
    public function prunable(): Builder
    {
        Log::debug('Calling Prune Device Tokens');

        return static::whereDate('created_at', '<', now()->subDays(180));
    }
}
