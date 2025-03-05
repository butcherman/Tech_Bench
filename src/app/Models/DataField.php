<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class DataField extends Model
{
    use HasFactory;

    /** @var string */
    protected $primaryKey = 'field_id';

    /** @var array<int, string> */
    protected $fillable = ['equip_id', 'type_id', 'order'];

    /** @var array<int, string> */
    protected $hidden = ['updated_at', 'created_at'];

    /*
    |---------------------------------------------------------------------------
    | Model Relationships
    |---------------------------------------------------------------------------
    */
    public function DataFieldType(): HasOne
    {
        return $this->hasOne(DataFieldType::class, 'type_id', 'type_id');
    }
}
