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

    //  TODO - ParentEquipmentType()

    public function ParentEquipment()
    {
        //  TODO - verify only shared equipment make it through
        return $this->hasMany('App\Models\CustomerEquipment', 'cust_id', 'parent_id')->where('shared', true);
    }

    public function CustomerEquipment()
    {
        return $this->hasMany('App\Models\CustomerEquipment', 'cust_id', 'cust_id');
    }

    public function Parent()
    {
        return $this->belongsTo('App\Models\Customer', 'parent_id', 'cust_id');
    }
}
