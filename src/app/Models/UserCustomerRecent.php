<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Prunable;
use Illuminate\Support\Facades\Log;

class UserCustomerRecent extends Model
{
    use Prunable;

    /***************************************************************************
     * Prune activity older than 90 days
     ***************************************************************************/
    public function prunable()
    {
        Log::debug('Calling Prune User Customer Recents');

        return static::whereDate('updated_at', '<', now()->subDays(90));
    }
}