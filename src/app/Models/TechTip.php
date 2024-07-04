<?php

namespace App\Models;

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
        return $this->hasManyThrough(
            EquipmentType::class,
            TechTipEquipment::class,
            'tip_id',
            'equip_id',
            'tip_id',
            'equip_id'
        );
    }

    public function TechTipType()
    {
        return $this->hasOne(TechTipType::class, 'tip_type_id', 'tip_type_id');
    }
}
