<?php

namespace App\Models;

use App\Observers\CustomerWorkbookObserver;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[ObservedBy([CustomerWorkbookObserver::class])]
class CustomerWorkbook extends Model
{
    use HasFactory;

    /** @var string */
    protected $primaryKey = 'wb_id';

    /** @var array<int, string> */
    protected $guarded = ['wb_id', 'created_at', 'updated_at'];

    /** @var array<int, string> */
    protected $appends = ['published'];

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
            'publish_until' => 'datetime:M d, Y',
            'created_at' => 'datetime:M d, Y',
            'updated_at' => 'datetime:M d, Y',
        ];
    }

    /*
    |---------------------------------------------------------------------------
    | Attributes
    |---------------------------------------------------------------------------
    */
    public function published(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->publish_until
            && Carbon::parse($this->publish_until) > Carbon::now(),
        );
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
