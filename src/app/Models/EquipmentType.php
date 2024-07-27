<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EquipmentType extends Model
{
    use HasFactory;

    protected $primaryKey = 'equip_id';

    protected $guarded = ['equip_id', 'created_at', 'updated_at'];

    protected $hidden = ['created_at', 'updated_at', 'pivot'];

    public function EquipmentCategory()
    {
        return $this->belongsTo(EquipmentCategory::class, 'cat_id', 'cat_id');
    }

    /**
     * When assigning equipment to a customer, specific data fields are used to enter customer specific information
     */
    public function DataFieldType()
    {
        return $this->hasManyThrough(
            DataFieldType::class,
            DataField::class,
            'equip_id',
            'type_id',
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
}
