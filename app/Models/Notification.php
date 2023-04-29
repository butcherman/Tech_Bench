<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Notifications\DatabaseNotification;

class Notification extends DatabaseNotification
{
    /**
     * @codeCoverageIgnore
     */
    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('M d, Y');
    }
}
