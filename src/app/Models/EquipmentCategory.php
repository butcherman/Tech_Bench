<?php

namespace App\Models;

use App\Observers\EquipmentCategoryObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[ObservedBy([EquipmentCategoryObserver::class])]
class EquipmentCategory extends Model
{
    use HasFactory;

    protected $primaryKey = 'cat_id';

    protected $guarded = ['cat_id', 'created_at', 'updated_at'];

    protected $hidden = ['created_at', 'updated_at'];

    /**
     * Model Relationships
     */
    public function EquipmentType()
    {
        return $this->hasMany(EquipmentType::class, 'cat_id', 'cat_id')
            ->orderBy('name', 'ASC');
    }
}
