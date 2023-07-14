<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EquipmentType extends Model
{
    use HasFactory;

    protected $primaryKey = 'equip_id';

    protected $fillable = ['cat_id', 'name'];

    protected $hidden = ['updated_at', 'created_at'];

    /**
     * Key for Route/Model binding
     */
    public function getRouteKeyName()
    {
        return 'equip_id';
    }

    /**
     * Each Equipment Type must belong to a category
     */
    public function EquipmentCategory()
    {
        return $this->belongsTo(EquipmentCategory::class, 'cat_id', 'cat_id');
    }

    /**
     * When assigning equipment to a customer, specific data fields are used to enter customer specific information
     */
    public function DataFieldType()
    {
        return $this->hasManyThrough(DataFieldType::class, DataField::class, 'equip_id', 'type_id', 'equip_id', 'type_id')->orderBy('order');
    }

    /**
     * Include a list of customers this equipment is tied to
     */
    public function Customer()
    {
        return $this->hasManyThrough(Customer::class, CustomerEquipment::class, 'equip_id', 'cust_id', 'equip_id', 'cust_id');
    }

    /**
     * Include a list of the Tech Tips this equipment is tied to
     */
    public function TechTip()
    {
        return $this->hasManyThrough(TechTip::class, TechTipEquipment::class, 'equip_id', 'tip_id', 'equip_id', 'tip_id');
    }
}
