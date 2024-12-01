<?php

namespace App\Models;

use App\Observers\EquipmentCategoryObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[ObservedBy([EquipmentCategoryObserver::class])]
class EquipmentCategory extends Model
{
    use HasFactory;

    /** @var string */
    protected $primaryKey = 'cat_id';

    /** @var array<int, string> */
    protected $guarded = ['cat_id', 'created_at', 'updated_at'];

    /** @var array<int, string> */
    protected $hidden = ['created_at', 'updated_at'];

    /*
    |---------------------------------------------------------------------------
    | Model Scopes
    |---------------------------------------------------------------------------
    */
    public function scopeEquipment(Builder $query): void
    {
        $query->with('EquipmentType');
    }

    public function scopePublicEquipment(Builder $query): void
    {
        $query->with([
            'EquipmentType' => function ($q) {
                $q->public();
            },
        ]);
    }

    /*
    |---------------------------------------------------------------------------
    | Model Relationships
    |---------------------------------------------------------------------------
    */
    public function EquipmentType(): HasMany
    {
        return $this->hasMany(EquipmentType::class, 'cat_id', 'cat_id')
            ->orderBy('name', 'ASC');
    }
}
