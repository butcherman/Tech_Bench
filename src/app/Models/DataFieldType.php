<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataFieldType extends Model
{
    use HasFactory;

    protected $primaryKey = 'type_id';

    protected $guarded = ['type_id', 'created_at', 'updated_at'];

    protected $hidden = ['updated_at', 'created_at', 'laravel_through_key'];

    protected $appends = ['in_use'];

    protected $casts = [
        'is_hyperlink' => 'boolean',
        'masked' => 'boolean',
    ];

    /***************************************************************************
     * Model Attributes
     ***************************************************************************/
    public function getInUseAttribute()
    {
        return DataField::where('type_id', $this->type_id)
            ->count() > 0 ? true : false;
    }
}
