<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserTechTipBookmark extends Model
{
    use HasFactory;

    protected $guarded    = ['id', 'created_at', 'updated_at'];
    protected $hidden     = ['updated_at', 'created_at', 'TechTip', 'user_id'];
    protected $appends    = ['subject', 'slug'];

    public function TechTip()
    {
        return $this->belongsTo(TechTip::class, 'tip_id', 'tip_id');
    }

    public function getSubjectAttribute()
    {
        return $this->TechTip->subject;
    }

    public function getSlugAttribute()
    {
        return $this->TechTip->slug;
    }
}
