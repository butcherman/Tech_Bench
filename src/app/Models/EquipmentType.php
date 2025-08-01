<?php

namespace App\Models;

use App\Observers\EquipmentTypeObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

#[ObservedBy([EquipmentTypeObserver::class])]
class EquipmentType extends Model
{
    use HasFactory;

    /** @var string */
    protected $primaryKey = 'equip_id';

    /** @var array<int, string> */
    protected $guarded = ['equip_id', 'created_at', 'updated_at'];

    /** @var array<int, string> */
    protected $hidden = ['created_at', 'updated_at', 'pivot'];

    /*
    |---------------------------------------------------------------------------
    | Model Casting
    |---------------------------------------------------------------------------
    */
    protected function casts(): array
    {
        return [
            'allow_public_tip' => 'boolean',
        ];
    }

    /*
    |---------------------------------------------------------------------------
    | Model Scopes
    |---------------------------------------------------------------------------
    */
    public function scopePublic(Builder $query): void
    {
        $query->where('allow_public_tip', true);
    }

    /*
    |---------------------------------------------------------------------------
    | Model Relationships
    |---------------------------------------------------------------------------
    */
    public function EquipmentCategory(): BelongsTo
    {
        return $this->belongsTo(EquipmentCategory::class, 'cat_id', 'cat_id');
    }

    public function DataFieldType(): BelongsToMany
    {
        return $this->belongsToMany(
            DataFieldType::class,
            'data_fields',
            'equip_id',
            'type_id'
        )->orderBy('order');
    }

    public function Customer(): HasManyThrough
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

    public function TechTip(): HasManyThrough
    {
        return $this->hasManyThrough(
            TechTip::class,
            TechTipEquipment::class,
            'equip_id',
            'tip_id',
            'equip_id',
            'tip_id'
        );
    }
}
