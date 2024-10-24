<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Prunable;
use Illuminate\Support\Facades\Log;

class UserInitialize extends Model
{
    use Prunable;

    protected $guarded = ['created_at', 'updated_at'];

    protected $hidden = ['id', 'created_at', 'updated_at'];

    /***************************************************************************
     * Route Model Binding Key
     ***************************************************************************/
    public function getRouteKeyName()
    {
        return 'token';
    }

    /***************************************************************************
     * Model Relationships
     ***************************************************************************/
    public function User()
    {
        return $this->hasOne(User::class, 'username', 'username');
    }

    /***************************************************************************
     * Prune models older than seven days
     ***************************************************************************/
    public function prunable()
    {
        Log::debug('Calling Prune User Initialize Links');

        return static::whereDate('created_at', '<', now()->subDays(7));
    }
}
