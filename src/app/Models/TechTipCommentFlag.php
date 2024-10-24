<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TechTipCommentFlag extends Model
{
    protected $guarded = ['id', 'updated_at', 'created_at'];

    protected $appends = ['flagged_by'];

    protected $hidden = ['User'];

    protected $casts = [
        'created_at' => 'datetime:M d, Y',
    ];

    /***************************************************************************
     * Model Attributes
     ***************************************************************************/
    public function getFlaggedByAttribute()
    {
        return $this->User->full_name;
    }

    /***************************************************************************
     * Model Relationships
     ***************************************************************************/
    protected function User()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }
}
