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
        return isset($this->TechTip->subject) ? $this->TechTip->subject : null;
    }

    public function getSlugAttribute()
    {
        return isset($this->TechTip->slug) ? $this->TechTip->slug : null;
    }
}
