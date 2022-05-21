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

    /**
     * Each Bookmark is tied to a Tech Tip ID
     * @codeCoverageIgnore
     */
    public function TechTip()
    {
        return $this->belongsTo(TechTip::class, 'tip_id', 'tip_id');
    }

    /**
     * Get the subject of the Tech Tip
     * @codeCoverageIgnore
     */
    public function getSubjectAttribute()
    {
        return isset($this->TechTip->subject) ? $this->TechTip->subject : null;
    }

    /**
     * Get the Slug for the link to the Tech Tip
     * @codeCoverageIgnore
     */
    public function getSlugAttribute()
    {
        return isset($this->TechTip->slug) ? $this->TechTip->slug : null;
    }
}
