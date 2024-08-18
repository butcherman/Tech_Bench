<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class TechTipComment extends Model
{
    use HasFactory;

    protected $primaryKey = 'comment_id';

    protected $guarded = ['id', 'updated_at', 'created_at'];

    protected $appends = ['author', 'is_flagged'];

    protected $hidden = ['User', 'Flags'];

    protected $casts = [
        'created_at' => 'datetime:M d, Y',
        'updated_at' => 'datetime:M d, Y',
        'flagged' => 'boolean',
    ];

    /***************************************************************************
     * Model Attributes
     ***************************************************************************/
    public function getIsFlaggedAttribute()
    {
        return $this->Flags->count() > 0 ? true : false;
    }

    public function getAuthorAttribute()
    {
        return $this->User->full_name;
    }

    /***************************************************************************
     * Model Relationships
     ***************************************************************************/
    public function User()
    {
        return $this->hasOne(User::class, 'user_id', 'user_id')->withTrashed();
    }

    public function TechTip()
    {
        return $this->belongsTo(TechTip::class, 'tip_id', 'tip_id');
    }

    public function Flags()
    {
        return $this->hasMany(TechTipCommentFlag::class, 'comment_id', 'comment_id');
    }

    /**
     * Additional Model Methods
     */

    /**
     * Flag a comment as inappropriate
     */
    public function flagComment()
    {
        $this->Flags()->save(new TechTipCommentFlag([
            'user_id' => Auth::user()->user_id,
            'comment_id' => $this->comment_id,
        ]));
    }
}
