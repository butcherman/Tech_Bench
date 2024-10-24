<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataField extends Model
{
    use HasFactory;

    protected $primaryKey = 'field_id';

    protected $fillable = ['equip_id', 'type_id', 'order'];

    protected $hidden = ['updated_at', 'created_at'];

    /***************************************************************************
     * Model Relationships
     ***************************************************************************/
    public function DataFieldType()
    {
        return $this->hasOne(DataFieldType::class, 'type_id', 'type_id');
    }
}
