<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EquipmentType extends Model
{
    use HasFactory;

    protected $primaryKey = 'equip_id';

    protected $guarded = ['equip_id', 'created_at', 'updated_at'];

    protected $hidden = ['created_at', 'updated_at'];

    public function EquipmentCategory()
    {
        return $this->belongsTo(EquipmentCategory::class, 'cat_id', 'cat_id');
    }
}
