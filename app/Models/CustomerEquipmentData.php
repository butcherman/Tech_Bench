<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerEquipmentData extends Model
{
    use HasFactory;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected $hidden = ['cust_equip_id', 'field_id', 'created_at', 'updated_at'];

    protected $appends = ['field_name', 'order'];

    /**
     * The name of the field this value data belongs to
     *
     * @codeCoverageIgnore
     */
    public function getFieldNameAttribute()
    {
        return DataField::with('DataFieldType')->find($this->field_id)->DataFieldType->name;
    }

    /**
     * The order that the data field should be in
     *
     * @codeCoverageIgnore
     */
    public function getOrderAttribute()
    {
        return DataField::find($this->field_id)->order;
    }
}
