<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Prunable;

class UserCustomerRecent extends Model
{
    use Prunable;

    /***************************************************************************
     * Prune activity older than 90 days
     ***************************************************************************/
    public function prunable()
    {
        return static::whereDate('updated_at', '<', now()->subDays(90));
    }
}
