<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Prunable;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Support\Facades\Log;

class UserCustomerRecent extends Pivot
{
    use Prunable;

    protected $table = 'user_customer_recents';

    /*
    |---------------------------------------------------------------------------
    | Prune activity older than 90 days
    |---------------------------------------------------------------------------
    */
    public function prunable()
    {
        Log::debug('Calling Prune User Customer Recents');

        return static::whereDate('updated_at', '<', now()->subDays(90));
    }
}
