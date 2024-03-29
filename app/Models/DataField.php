<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DataField extends Model
{
    use HasFactory;

    protected $primaryKey = 'field_id';
    protected $fillable   = ['equip_id', 'type_id', 'order'];
    protected $hidden     = ['updated_at', 'created_at'];

    /**
     * Name of the data field that is attached to this equipment type
     */
    public function DataFieldType()
    {
        return $this->hasOne('App\Models\DataFieldType', 'type_id', 'type_id');
    }
}
