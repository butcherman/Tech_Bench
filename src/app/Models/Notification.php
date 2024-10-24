<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Notifications\DatabaseNotification;

class Notification extends DatabaseNotification
{
    protected $hidden = ['notifiable_id', 'notifiable_type', 'updated_at'];

    /***************************************************************************
     * Modify Date Casting to include Weekday and Time
     *
     * @codeCoverageIgnore
     ***************************************************************************/
    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('M d, Y');
    }
}
