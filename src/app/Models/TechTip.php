<?php

namespace App\Models;

use App\Observers\TechTipObserver;
use App\Traits\Models\HasBookmarks;
use App\Traits\Models\HasRecents;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;

#[ObservedBy([TechTipObserver::class])]
class TechTip extends Model
{
    use HasBookmarks;
    use HasFactory;
    use HasRecents;
    use Searchable;
    use SoftDeletes;

    /** @var string */
    protected $primaryKey = 'tip_id';

    /** @var array<int, string> */
    protected $guarded = ['tip_id', 'updated_at', 'created_at'];

    /** @var array<int, string> */
    protected $hidden = [
        'Bookmarks',
        'deleted_at',
        'Recent',
        'TechTipType',
        'TechTipViews',
        'tip_type_id',
    ];

    /** @var array<int, string> */
    protected $appends = [
        'href',
        'public_href',
        'type',
        'views'
    ];

    /*
    |---------------------------------------------------------------------------
    | Model Casting
    |---------------------------------------------------------------------------
    */
    protected function casts(): array
    {
        return [
            'created_at' => 'datetime:M d, Y',
            'updated_at' => 'datetime:M d, Y',
            'deleted_at' => 'datetime:M d, Y',
            'sticky' => 'boolean',
            'public' => 'boolean',
            'allow_comments' => 'boolean',
        ];
    }

    /*
    |---------------------------------------------------------------------------
    | For Route/Model binding, we will use either the slug or tip_id
    |---------------------------------------------------------------------------
    */
    public function resolveRouteBinding($value, $field = null): TechTip
    {
        return $this->where('slug', $value)
            ->orWhere('tip_id', $value)
            ->firstOrFail();
    }

    /*
    |---------------------------------------------------------------------------
    | Model Attributes
    |---------------------------------------------------------------------------
    */
    public function views(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->TechTipViews->views,
        );
    }

    public function href(): Attribute
    {
        return Attribute::make(
            get: fn() => route('tech-tips.show', $this->slug),
        );
    }

    public function publicHref(): ?Attribute
    {
        return Attribute::make(
            get: fn() => $this->public
                ? route('publicTips.show', $this->slug)
                : null,
        );
    }

    public function type(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->TechTipType->description,
        );
    }

    /*
    |---------------------------------------------------------------------------
    | Model Relationships
    |---------------------------------------------------------------------------
    */
    public function CreatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id')
            ->withTrashed();
    }

    public function UpdatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_id', 'user_id')
            ->withTrashed();
    }

    public function Equipment(): BelongsToMany
    {
        return $this->belongsToMany(
            EquipmentType::class,
            TechTipEquipment::class,
            'tip_id',
            'equip_id'
        )->withTimestamps();
    }

    public function PublicEquipment(): BelongsToMany
    {
        return $this->belongsToMany(
            EquipmentType::class,
            TechTipEquipment::class,
            'tip_id',
            'equip_id'
        )->public()->withTimestamps();
    }

    public function Files(): BelongsToMany
    {
        return $this->belongsToMany(
            FileUpload::class,
            TechTipFile::class,
            'tip_id',
            'file_id',
        );
    }

    public function TechTipType(): HasOne
    {
        return $this->hasOne(TechTipType::class, 'tip_type_id', 'tip_type_id');
    }

    public function Comments(): HasMany
    {
        return $this->hasMany(TechTipComment::class, 'tip_id', 'tip_id');
    }

    public function Bookmarks(): BelongsToMany
    {
        return $this->belongsToMany(
            User::class,
            'user_tech_tip_bookmarks',
            'tip_id',
            'user_id',
        )->withTimestamps();
    }

    public function Recent(): BelongsToMany
    {
        return $this->belongsToMany(
            User::class,
            'user_tech_tip_recents',
            'tip_id',
            'user_id'
        )->withTimestamps();
    }

    public function TechTipViews(): HasOne
    {
        return $this->hasOne(TechTipView::class, 'tip_id', 'tip_id');
    }

    /*
    |---------------------------------------------------------------------------
    | Additional Model Methods
    |---------------------------------------------------------------------------
    */

    /**
     * Increase the view counter
     */
    public function wasViewed(): void
    {
        $this->TechTipViews->increment('views');
    }

    /*
    |---------------------------------------------------------------------------
    | Meilisearch Search Data
    |---------------------------------------------------------------------------
    */

    /**
     * Search Results for Meilisearch
     *
     * @codeCoverageIgnore
     */
    public function toSearchableArray()
    {
        return [
            'tip_id' => (int) $this->tip_id,
            'subject' => $this->subject,
            'details' => $this->details,
            'public' => $this->public,
            'tip_type_id' => $this->tip_type_id,
            'Equipment' => $this->Equipment,
        ];
    }

    /**
     * Add Relationships to Meilisearch search results
     *
     * @codeCoverageIgnore
     */
    protected function makeAllSearchableUsing(Builder $query)
    {
        return $query->with('Equipment');
    }
}
