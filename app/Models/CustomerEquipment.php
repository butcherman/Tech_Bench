<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CustomerEquipment extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $primaryKey = 'cust_equip_id';
    protected $guarded    = ['cust_equip_id', 'updated_at', 'created_at'];
    protected $hidden     = ['created_at', 'updated_at', 'deleted_at', 'cust_id'];
    protected $appends    = ['name'];

    public function getNameAttribute()
    {
        return EquipmentType::find($this->equip_id)->name;
    }

    public function CustomerEquipmentData()
    {
        return $this->hasMany('App\Models\CustomerEquipmentData', 'cust_equip_id', 'cust_equip_id');
    }
}
