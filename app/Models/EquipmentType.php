<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EquipmentType extends Model
{
    use HasFactory;

    protected $primaryKey = 'equip_id';
    protected $fillable   = ['cat_id', 'name'];
    protected $hidden     = ['updated_at', 'created_at'];

    /*
    *   Each Equipment Type must belong to a category
    */
    public function EquipmentCategory()
    {
        return $this->belongsTo('App\Models\EquipmentCategory', 'cat_id', 'cat_id');
    }

    /*
    *   When assigning equipment to a customer, specific data fields are used to enter customer specific information
    */
    public function DataFieldType()
    {
        return $this->hasManyThrough('App\Models\DataFieldType', 'App\Models\DataField', 'equip_id', 'type_id', 'equip_id', 'type_id')->orderBy('order');
    }
}
