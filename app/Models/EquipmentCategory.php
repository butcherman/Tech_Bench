<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EquipmentCategory extends Model
{
    use HasFactory;

    protected $primaryKey = 'cat_id';

    protected $fillable = ['name'];

    protected $hidden = ['updated_at', 'created_at'];

    /**
     * Key for Route/Model binding
     */
    public function getRouteKeyName()
    {
        return 'cat_id';
    }

    /**
     * Each Equipment Category can have several types of equipment assigned to it
     *
     * @codeCoverageIgnore
     */
    public function EquipmentType()
    {
        return $this->hasMany('App\Models\EquipmentType', 'cat_id', 'cat_id')->orderBy('name');
    }
}
