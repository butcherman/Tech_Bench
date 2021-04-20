<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerEquipmentData extends Model
{
    use HasFactory;

    protected $guarded = ['id', 'created_at', 'updated_at'];
    protected $hidden  = ['id', 'cust_equip_id', 'field_id', 'created_at', 'updated_at'];
    protected $appends = ['field_name'];

    public function getFieldNameAttribute()
    {
        return DataField::with('DataFieldType')->find($this->field_id)->DataFieldType->name;
    }
}
