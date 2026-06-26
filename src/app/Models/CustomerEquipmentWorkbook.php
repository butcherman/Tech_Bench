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
    protected $appends = ['published', 'publish_until_raw', 'parsed_workbook'];

    /** @var array<int, string> */
    protected $hidden = ['wb_id', 'cust_id', 'cust_equip_id', 'wb_skeleton'];

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
            'wb_skeleton' => 'array',
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
            get: fn () => Carbon::now() > $this->publish_until ? false : true
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

    public function parsedWorkbook(): Attribute
    {
        $equip = CustomerEquipment::where('cust_equip_id', $this->cust_equip_id)
            ->first();
        $equipName = $equip ? $equip->equipName : null;

        $placeholders = [
            'customer_name' => $this->Customer ? $this->Customer->name : null,
            'equipment_name' => $equipName,
        ];

        return Attribute::make(
            get: fn () => $this->replacePlaceholders(
                $this->wb_skeleton,
                $placeholders
            ),
        );
    }

    public function publicWorkbook(): Attribute
    {
        $workbook = $this->parsedWorkbook;

        foreach ($workbook['body'] as $key => $page) {
            if (! $page['canPublish']) {
                unset($workbook['body'][$key]);
            }
        }

        return Attribute::make(
            get: fn () => $workbook
        );
    }

    public function upToDate(): Attribute
    {
        $equip = CustomerEquipment::where('cust_equip_id', $this->cust_equip_id)
            ->first();

        return Attribute::make(
            get: fn () => $this->wb_version === $equip->EquipmentType
                ->EquipmentWorkbook
                ->version_hash,
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

    public function WorkbookTableValues(): HasMany
    {
        return $this->hasMany(WorkbookTableValue::class, 'wb_id', 'wb_id');
    }

    public function PublicWorkbookValues(): HasMany
    {
        return $this->WorkbookValues()->where('public', true);
    }

    public function PublicWorkbookTableValues(): HasMany
    {
        return $this->WorkbookTableValues()->where('public', true);
    }

    public function Customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'cust_id', 'cust_id');
    }

    public function CustomerEquipment(): BelongsTo
    {
        return $this->belongsTo(
            CustomerEquipment::class,
            'cust_equip_id',
            'cust_equip_id'
        );
    }

    /*
    |---------------------------------------------------------------------------
    | Additional Methods
    |---------------------------------------------------------------------------
    */

    /**
     * Replace any placeholder data in the Workbook Skeleton with the proper
     * information.
     */
    protected function replacePlaceholders(mixed $value, array $data): mixed
    {
        if (is_array($value)) {
            return array_map(
                fn ($item) => $this->replacePlaceholders($item, $data),
                $value
            );
        }

        if (is_string($value)) {
            return preg_replace_callback(
                '/\{\{([^}]+)\}\}/',
                fn ($matches) => $data[$matches[1]] ?? $matches[0],
                $value
            );
        }

        return $value;
    }
}
