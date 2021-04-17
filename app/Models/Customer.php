<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Customer extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $primaryKey = 'cust_id';
    protected $guarded    = ['updated_at', 'created_at', 'deleted_at'];
    protected $hidden     = ['updated_at', 'created_at', 'deleted_at'];

    public function EquipmentType()
    {
        return $this->hasManyThrough('App\Models\EquipmentType', 'App\Models\CustomerEquipment', 'cust_id', 'equip_id', 'cust_id', 'equip_id');
    }

    public function ParentEquipment()
    {
        return $this->hasManyThrough('App\Models\EquipmentType', 'App\Models\CustomerEquipment', 'cust_id', 'equip_id', 'parent_id', 'equip_id');
    }

    public function Parent()
    {
        return $this->belongsTo('App\Models\Customer', 'parent_id', 'cust_id');
    }
}
