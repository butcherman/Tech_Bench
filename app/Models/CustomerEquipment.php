<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CustomerEquipment extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $primaryKey = 'cust_equip_id';
    protected $guarded    = ['cust_equip_id', 'updated_at', 'created_at'];
    protected $hidden     = ['created_at', 'updated_at', 'deleted_at', 'cust_id'];
    protected $appends    = ['name'];
    protected $with       = ['CustomerEquipmentData'];
    protected $casts      = [
        'shared'     => 'boolean',
        'deleted_at' => 'datetime:M d, Y',
    ];

    /**
     * Get the name of the equipment without attaching the entire equipment object
     */
    public function getNameAttribute()
    {
        return EquipmentType::find($this->equip_id)->name;
    }

    /**
     * Site specific information for the selected piece of equipment
     */
    public function CustomerEquipmentData()
    {
        return $this->hasMany('App\Models\CustomerEquipmentData', 'cust_equip_id', 'cust_equip_id');
    }

    /**
     * Return the soft deleted items re-formatted to match other models
     */
    public static function getTrashed(Customer $customer)
    {
        $data = self::where('cust_id', $customer->cust_id)
                      ->onlyTrashed()
                      ->get()
                      ->map(function($item) {
            return [
                'item_id'      => $item->cust_equip_id,
                'item_name'    => $item->name,
                'item_deleted' => $item->deleted_at->toFormattedDateString(),
            ];
        });

        return $data;
    }
}
