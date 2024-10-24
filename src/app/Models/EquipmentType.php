<?php

namespace App\Models;

use App\Observers\EquipmentTypeObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[ObservedBy([EquipmentTypeObserver::class])]
class EquipmentType extends Model
{
    use HasFactory;

    protected $primaryKey = 'equip_id';

    protected $guarded = ['equip_id', 'created_at', 'updated_at'];

    protected $hidden = ['created_at', 'updated_at', 'pivot'];

    protected $casts = [
        'allow_public_tip' => 'boolean',
    ];

    /**
     * Model Relationships
     */
    public function EquipmentCategory()
    {
        return $this->belongsTo(EquipmentCategory::class, 'cat_id', 'cat_id');
    }

    public function DataFieldType()
    {
        return $this->belongsToMany(
            DataFieldType::class,
            'data_fields',
            'equip_id',
            'type_id'
        )->orderBy('order');
    }

    public function Customer()
    {
        return $this->hasManyThrough(
            Customer::class,
            CustomerEquipment::class,
            'equip_id',
            'cust_id',
            'equip_id',
            'cust_id'
        );
    }

    public function TechTip()
    {
        return $this->hasManyThrough(
            TechTip::class,
            TechTipEquipment::class,
            'equip_id',
            'tip_id',
            'equip_id',
            'tip_id'
        );
    }
}
