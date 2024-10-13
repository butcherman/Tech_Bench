<?php

namespace App\Models;

use App\Observers\CustomerEquipmentDataObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[ObservedBy([CustomerEquipmentDataObserver::class])]
class CustomerEquipmentData extends Model
{
    use HasFactory;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected $hidden = ['cust_equip_id', 'field_id', 'created_at', 'updated_at', 'DataField'];

    protected $appends = ['field_name', 'order'];

    /***************************************************************************
     * Model Attributes
     ***************************************************************************/
    public function getFieldNameAttribute()
    {
        // The name of the field this value data belongs to
        return $this->DataFieldType->name;
    }

    public function getOrderAttribute()
    {
        // The order that the data field should be in
        return $this->DataField->order;
    }

    /***************************************************************************
     * Model Relationships
     ***************************************************************************/
    public function DataField()
    {
        return $this->hasOne(DataField::class, 'field_id', 'field_id');
    }

    public function DataFieldType()
    {
        return $this->hasOneThrough(
            DataFieldType::class,
            DataField::class,
            'field_id',
            'type_id',
            'field_id',
            'type_id'
        );
    }
}
