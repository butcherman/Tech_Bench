<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserTechTipRecent extends Model
{
    use HasFactory;

    protected $guarded = ['id', 'updated_at', 'created_at'];
    protected $hidden  = ['id', 'user_id', 'created_at', 'TechTip'];
    protected $appends = ['subject', 'slug'];

    /**
     * Each Recent is tied to a Tech tip ID
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
        // return isset($this->TechTip->subject) ? $this->TechTip->subject : null;
        return $this->TechTip->subject;
    }

    /**
     * Get the Slug for the link to the Tech Tip
     * @codeCoverageIgnore
     */
    public function getSlugAttribute()
    {
        // return isset($this->TechTip->slug) ? $this->TechTip->slug : null;
        return $this->TechTip->slug;
    }
}
