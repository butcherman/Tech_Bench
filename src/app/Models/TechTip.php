<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;

class TechTip extends Model
{
    use HasFactory;
    use Searchable;
    use SoftDeletes;

    protected $primaryKey = 'tip_id';

    protected $guarded = ['tip_id', 'updated_at', 'created_at'];

    protected $hidden = ['deleted_at', 'tip_type_id', 'Bookmarks'];

    protected $appends = ['href', 'public_href', 'equipList', 'fileList'];

    protected $casts = [
        'created_at' => 'datetime:M d, Y',
        'updated_at' => 'datetime:M d, Y',
        'deleted_at' => 'datetime:M d, Y',
        'sticky' => 'boolean',
        'public' => 'boolean',
    ];

    /**
     * For Route/Model binding, we will use either the slug or tip_id
     */
    public function resolveRouteBinding($value, $field = null)
    {
        return $this->where('slug', $value)
            ->orWhere('tip_id', $value)
            ->firstOrFail();
    }

    public function getHrefAttribute()
    {
        return route('tech-tips.show', $this->slug);
    }

    public function getPublicHrefAttribute()
    {
        if ($this->public) {
            return route('publicTips.show', $this->slug);
        }
    }

    protected function getEquipListAttribute()
    {
        return $this->EquipmentType->pluck('equip_id')->toArray();
    }

    protected function getFileListAttribute()
    {
        return $this->FileUpload->pluck('file_id')->toArray();
    }

    public function CreatedBy()
    {
        return $this->hasOne(User::class, 'user_id', 'user_id')
            ->withTrashed();
    }

    public function UpdatedBy()
    {
        return $this->hasOne(User::class, 'user_id', 'updated_id')
            ->withTrashed();
    }

    public function EquipmentType()
    {
        return $this->belongsToMany(
            EquipmentType::class,
            'tech_tip_equipment',
            'tip_id',
            'equip_id'
        )->withTimestamps();
    }

    public function FileUpload()
    {
        return $this->belongsToMany(
            FileUpload::class,
            'tech_tip_files',
            'tip_id',
            'file_id'
        )->withTimestamps();
    }

    public function TechTipType()
    {
        return $this->hasOne(TechTipType::class, 'tip_type_id', 'tip_type_id');
    }

    public function TechTipComment()
    {
        return $this->hasMany(TechTipComment::class, 'tip_id', 'tip_id');
    }

    public function Bookmarks()
    {
        return $this->belongsToMany(
            User::class,
            'user_tech_tip_bookmarks',
            'tip_id',
            'user_id',
        );
    }

    public function Recent()
    {
        return $this->belongsToMany(
            User::class,
            'user_tech_tip_recents',
            'tip_id',
            'user_id'
        )->withTimestamps();
    }

    public function isFav(User $user)
    {
        $bookmarks = $this->Bookmarks->pluck('user_id')->toArray();

        return in_array($user->user_id, $bookmarks);
    }

    /**
     * Update the Users Recent Tech Tip activity
     */
    public function touchRecent(User $user)
    {
        $isRecent = $this->Recent->where('user_id', $user->user_id)->first();
        if ($isRecent) {
            $this->Recent()->detach($user);
        }
        $this->Recent()->attach($user);

    }

    /**
     * Search Results for Meilisearch
     * 
     * @codeCoverageIgnore
     */
    public function toSearchableArray()
    {
        return [
            'tip_id' => (int) $this->tip_id,
            'subject' => $this->subject,
            'details' => $this->details,
            'public' => $this->public,
            'tip_type_id' => $this->tip_type_id,
            'EquipmentType' => $this->EquipmentType,
        ];
    }

    /**
     * Add Relationships to search
     * 
     * @codeCoverageIgnore
     */
    protected function makeAllSearchableUsing(Builder $query)
    {
        return $query->with('EquipmentType');
    }
}
