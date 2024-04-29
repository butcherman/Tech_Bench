<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserLogins extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';

    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected $hidden = ['id', 'updated_at'];

    protected $casts = [
        'created_at' => 'date',
        'updated_at' => 'date',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->toDayDateTimeString();
    }
}
