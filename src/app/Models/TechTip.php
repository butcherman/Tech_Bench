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
    use SoftDeletes;
    use Searchable;

    protected $primaryKey = 'tip_id';
    protected $guarded = ['tip_id', 'updated_at', 'created_at'];
    protected $hidden = ['deleted_at', 'tip_type_id'];
    protected $appends = ['href'];
    protected $casts = [
        'created_at' => 'datetime:M d, Y',
        'updated_at' => 'datetime:M d, Y',
        'deleted_at' => 'datetime:M d, Y',
        'sticky' => 'boolean',
    ];

    public function getHrefAttribute()
    {
        return route('tech-tips.show', $this->slug);
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

    public function TechTipType()
    {
        return $this->hasOne(TechTipType::class, 'tip_type_id', 'tip_type_id');
    }

    /**
     * Search Results for Meilisearch
     */
    public function toSearchableArray()
    {
        return [
            'tip_id' => (int) $this->tip_id,
            'subject' => $this->subject,
            'details' => $this->details,
            'tip_type_id' => $this->tip_type_id,
            'EquipmentType' => $this->EquipmentType,
        ];
    }

    protected function makeAllSearchableUsing(Builder $query)
    {
        return $query->with('EquipmentType');
    }
}
