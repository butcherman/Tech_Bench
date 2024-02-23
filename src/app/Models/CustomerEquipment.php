<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CustomerEquipment extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $primaryKey = 'cust_equip_id';

    protected $guarded = ['updated_at', 'created_at'];

    protected $hidden = [
        'deleted_at',
        'created_at',
        'updated_at',
        'equip_id',
        'EquipmentType'
    ];

    protected $appends = ['equip_name'];

    public function getEquipNameAttribute()
    {
        return $this->EquipmentType->name;
    }

    public function EquipmentType()
    {
        return $this->belongsTo(EquipmentType::class, 'equip_id', 'equip_id');
    }

    public function CustomerSite()
    {
        return $this->belongsToMany(
            CustomerSite::class,
            'customer_site_equipment',
            'cust_equip_id',
            'cust_site_id'
        );
    }
}
