<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CustomerEquipmentData extends Model
{
    use HasFactory;

    protected $guarded = ['id', 'created_at', 'updated_at'];
    protected $hidden  = ['cust_equip_id', 'field_id', 'created_at', 'updated_at'];
    protected $appends = ['field_name'];

    /*
    *   The name of the field this value data belongs to
    */
    public function getFieldNameAttribute()
    {
        return DataField::with('DataFieldType')->find($this->field_id)->DataFieldType->name;
    }
}
