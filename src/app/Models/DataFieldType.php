<?php

namespace App\Models;

use App\Observers\DataFieldTypeObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[ObservedBy([DataFieldTypeObserver::class])]
class DataFieldType extends Model
{
    use HasFactory;

    /** @var string */
    protected $primaryKey = 'type_id';

    /** @var array<int, string> */
    protected $guarded = ['type_id', 'created_at', 'updated_at'];

    /** @var array<int, string> */
    protected $hidden = [
        'updated_at',
        'created_at',
        'laravel_through_key',
        'pivot',
    ];

    /*
    |---------------------------------------------------------------------------
    | Model Casting
    |---------------------------------------------------------------------------
    */
    protected function casts(): array
    {
        return [
            'is_hyperlink' => 'boolean',
            'masked' => 'boolean',
            'allow_copy' => 'boolean',
            'do_not_log_value' => 'boolean',
        ];
    }

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
