<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TechTipComment extends Model
{
    use HasFactory;

    protected $guarded = ['id', 'updated_at', 'created_at'];
    protected $appends = ['author'];
    protected $hidden = ['User'];
    protected $casts = [
        'created_at' => 'datetime:M d, Y',
        'updated_at' => 'datetime:M d, Y',
        'flagged' => 'boolean',
    ];

    public function User()
    {
        return $this->hasOne(User::class, 'user_id', 'user_id')->withTrashed();
    }

    public function getAuthorAttribute()
    {
        return $this->User->full_name;
    }
}
