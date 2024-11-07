<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataFieldType extends Model
{
    use HasFactory;

    /** @var string */
    protected $primaryKey = 'type_id';

    /** @var array<int, string> */
    protected $guarded = ['type_id', 'created_at', 'updated_at'];

    /** @var array<int, string> */
    protected $hidden = ['updated_at', 'created_at', 'laravel_through_key'];

    /** @var array<int, string> */
    protected $casts = [
        'is_hyperlink' => 'boolean',
        'masked' => 'boolean',
        'allow_copy' => 'boolean',
    ];

    /*
    |---------------------------------------------------------------------------
    | Model Attributes
    |---------------------------------------------------------------------------
    */
    public function inUse(): Attribute
    {
        return Attribute::make(
            get: fn () => DataField::where('type_id', $this->type_id)
                ->count() > 0,
        );
    }
}
