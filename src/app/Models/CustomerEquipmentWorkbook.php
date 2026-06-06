<?php

namespace App\Models;

use App\Observers\CustomerEquipmentWorkbookObserver;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[ObservedBy([CustomerEquipmentWorkbookObserver::class])]
class CustomerEquipmentWorkbook extends Model
{
    use HasFactory;

    /** @var string */
    protected $primaryKey = 'wb_id';

    /** @var array<int, string> */
    protected $guarded = ['wb_id', 'created_at', 'updated_at'];

    /** @var array<int, string> */
    protected $appends = ['published', 'publish_until_raw'];

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
    | Model Attributes
    |---------------------------------------------------------------------------
    */
    public function published(): Attribute
    {
        return Attribute::make(
            get: fn () => is_null($this->publish_until)
                && $this->publish_until > Carbon::now() ? false : true,
        );
    }

    public function publishUntilRaw(): Attribute
    {
        $originalValue = $this->getRawOriginal('publish_until');
        preg_match('/\d{4}-\d{2}-\d{2}/', $originalValue, $parsedDate);

        return Attribute::make(
            get: fn () => $parsedDate[0] ?? null,
        );
    }

    /*
    |---------------------------------------------------------------------------
    | Relationships
    |---------------------------------------------------------------------------
    */
    public function WorkbookValues(): HasMany
    {
        return $this->hasMany(WorkbookValue::class, 'wb_id', 'wb_id');
    }

    public function WorkbookTableValeus(): HasMany
    {
        return $this->hasMany(WorkbookTableValue::class, 'wb_id', 'wb_id');
    }

    public function PublicWorkbookValues(): HasMany
    {
        return $this->WorkbookValues()->where('protected', false);
    }

    public function PublicWorkbookTableValeus(): HasMany
    {
        return $this->WorkbookTableValues()->where('protected', false);
    }

    public function Customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'cust_id', 'cust_id');
    }

    public function CustomerEquipment(): BelongsTo
    {
        return $this->belongsTo(CustomerEquipment::class, 'cust_equip_id', 'cust_equip_id');
    }
}
