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
    protected $hidden = ['deleted_at', 'tip_type_id', 'Bookmarks'];

    /** @var array<int, string> */
    protected $appends = ['href', 'public_href', 'equip_list', 'file_list'];

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
    public function href(): Attribute
    {
        return Attribute::make(
            get: fn () => route('tech-tips.show', $this->slug),
        );
    }

    public function publicHref(): ?Attribute
    {
        return Attribute::make(
            get: fn () => $this->is_public
                ? route('publicTips.show', $this->slug)
                : null,
        );

    }

    protected function equipList(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->EquipmentType->pluck('equip_id')->toArray(),
        );
    }

    protected function fileList(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->FileUpload->pluck('file_id')->toArray(),
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

    public function EquipmentType(): BelongsToMany
    {
        return $this->belongsToMany(
            EquipmentType::class,
            TechTipEquipment::class,
            'tip_id',
            'equip_id'
        )->withTimestamps();
    }

    public function FileUpload(): BelongsToMany
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

    public function TechTipComment(): HasMany
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

    public function TechTipView(): HasOne
    {
        return $this->hasOne(TechTipView::class, 'tip_id', 'tip_id');
    }

    /*
    |---------------------------------------------------------------------------
    | Additional Model Methods
    |---------------------------------------------------------------------------
    */

    /**
     * Load data needed to show the Tech Tip to user
     */
    // public function loadShowData(bool $isDeleted = false)
    // {
    //     $this->load(['CreatedBy', 'UpdatedBy', 'TechTipType', 'TechTipView'])
    //         ->CreatedBy->makeHidden(['email', 'initials', 'role_name', 'username']);

    //     // If Tip has been updated, load user data for who updated it
    //     if ($this->UpdatedBy) {
    //         $this->UpdatedBy
    //             ->makeHidden(['email', 'initials', 'role_name', 'username']);
    //     }

    //     // if (! $isDeleted) {
    //     //     // Increase Views counter
    //     //     $this->TechTipView->increment('views');

    //     //     // Add Tip to users Recent visits
    //     //     if (request()->user()) {
    //     //         $this->touchRecent(request()->user());
    //     //     }
    //     // }
    // }

    /**
     * Load and hide data needed for Public Viewing
     */
    // public function loadPublicData()
    // {
    //     $this->makeHidden([
    //         'user_id',
    //         'updated_id',
    //         'sticky',
    //         'allow_comments',
    //         'slug',
    //         'views',
    //         'href',
    //         'equipList',
    //         'fileList',
    //     ]);
    //     $this->load([
    //         'EquipmentType' => function ($q) {
    //             $q->where('allow_public_tip', true);
    //         },
    //     ]);
    //     $this->EquipmentType->makeHidden([
    //         'allow_public_tip',
    //         'cat_id',
    //         'equip_id',
    //     ]);
    //     $this->load([
    //         'FileUpload' => function ($q) {
    //             $q->where('public', true);
    //         },
    //     ]);
    //     $this->FileUpload->makeHidden([
    //         'file_size',
    //         'pivot',
    //     ]);
    // }

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
            'EquipmentType' => $this->EquipmentType,
        ];
    }

    /**
     * Add Relationships to Meilisearch search results
     *
     * @codeCoverageIgnore
     */
    protected function makeAllSearchableUsing(Builder $query)
    {
        return $query->with('EquipmentType');
    }
}
