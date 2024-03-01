<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerEquipmentData extends Model
{
    use HasFactory;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected $hidden = ['cust_equip_id', 'field_id', 'created_at', 'updated_at', 'DataField'];

    protected $appends = ['field_name', 'order'];

    /**
     * The name of the field this value data belongs to
     */
    public function getFieldNameAttribute()
    {
        return $this->DataFieldType->name;
    }

    /**
     * The order that the data field should be in
     */
    public function getOrderAttribute()
    {
        return $this->DataField->order;
    }

    public function DataField()
    {
        return $this->hasOne(DataField::class, 'field_id', 'field_id');
    }

    public function DataFieldType()
    {
        return $this->hasOneThrough(DataFieldType::class, DataField::class, 'field_id', 'type_id', 'field_id', 'type_id');
    }
}
