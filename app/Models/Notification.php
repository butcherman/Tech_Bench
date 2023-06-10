<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Notifications\DatabaseNotification;

class Notification extends DatabaseNotification
{
    protected $hidden = ['notifiable_id', 'notifiable_type', 'updated_at', 'type'];

    /**
     * @codeCoverageIgnore
     */
    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('M d, Y');
    }
}
