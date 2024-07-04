<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EquipmentCategory extends Model
{
    use HasFactory;

    protected $primaryKey = 'cat_id';

    protected $guarded = ['cat_id', 'created_at', 'updated_at'];

    protected $hidden = ['created_at', 'updated_at'];

    public function EquipmentType()
    {
        return $this->hasMany(EquipmentType::class, 'cat_id', 'cat_id')
            ->orderBy('name', 'ASC');
    }
}
