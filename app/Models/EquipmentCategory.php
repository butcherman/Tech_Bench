<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EquipmentCategory extends Model
{
    use HasFactory;

    protected $primaryKey = 'cat_id';
    protected $fillable   = ['name'];
    protected $hidden     = ['updated_at', 'created_at'];

    /*
    *   Each Equipment Category can have several types of equipment assigned to it
    */
    public function EquipmentType()
    {
        return $this->hasMany('App\Models\EquipmentType', 'cat_id', 'cat_id');
    }
}
