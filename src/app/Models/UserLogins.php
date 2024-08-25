<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Prunable;
use Illuminate\Support\Facades\Log;

class UserLogins extends Model
{
    use Prunable;

    protected $primaryKey = 'id';

    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected $hidden = ['id', 'updated_at'];

    protected $casts = [
        'created_at' => 'date',
        'updated_at' => 'date',
    ];

    /**
     * Format time and date string
     *
     * @codeCoverageIgnore
     */
    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->toDayDateTimeString();
    }

    /***************************************************************************
     * Prune activity older than xx days (730 by default)
     ***************************************************************************/
    public function prunable()
    {
        Log::debug('Calling Prune User Logins');

        return static::whereDate(
            'updated_at',
            '<',
            now()->subDays(config('auth.login_history_lifespan'))
        );
    }
}
