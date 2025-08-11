<?php

namespace App\Models;

use App\Observers\CustomerWorkbookObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[ObservedBy([CustomerWorkbookObserver::class])]
class CustomerWorkbook extends Model
{
    /** @var string */
    protected $primaryKey = 'wb_id';

    /** @var array<int, string> */
    protected $guarded = ['wb_id', 'created_at', 'updated_at'];

    public function getRouteKeyName(): string
    {
        return 'wb_hash';
    }

    /*
    |---------------------------------------------------------------------------
    | Model Casting
    |---------------------------------------------------------------------------
    */
    protected function casts(): array
    {
        return [
            'published' => 'boolean',
            'by_invite_only' => 'boolean',
            'publish_until' => 'datetime:M d, Y',
            'created_at' => 'datetime:M d, Y',
            'updated_at' => 'datetime:M d, Y',
        ];
    }

    /*
    |---------------------------------------------------------------------------
    | Relationships
    |---------------------------------------------------------------------------
    */
    public function WorkbookValues(): HasMany
    {
        return $this->hasMany(CustomerWorkbookValue::class, 'wb_id', 'wb_id');
    }
}