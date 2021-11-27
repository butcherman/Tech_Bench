<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\DatabaseNotification;
use DateTimeInterface;

class Notification extends DatabaseNotification
{
    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('M d, Y');
    }
}
