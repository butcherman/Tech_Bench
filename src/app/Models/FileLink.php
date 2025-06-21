<?php

namespace App\Models;

use App\Exceptions\FileLink\FileLinkExpiredException;
use App\Observers\FileLinkObserver;
use Carbon\Carbon;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Prunable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\Log;

#[ObservedBy([FileLinkObserver::class])]
class FileLink extends Model
{
    use HasFactory;
    use Prunable;

    /** @var string */
    protected $primaryKey = 'link_id';

    /** @var array<int, string> */
    protected $guarded = ['link_id', 'created_at', 'updated_at'];

    /** @var array<int, string> */
    protected $hidden = ['user_id', 'created_at', 'updated_at'];

    /** @var array<int, string> */
    protected $appends = ['is_expired', 'href', 'public_href', 'created_stamp'];

    /*
    |---------------------------------------------------------------------------
    | Model Casting
    |---------------------------------------------------------------------------
    */
    public function casts(): array
    {
        return [
            'allow_upload' => 'boolean',
            'created_at' => 'datetime:M d, Y',
            'updated_at' => 'datetime:M d, Y',
            'expire' => 'datetime:M d, Y',
        ];
    }

    /*
    |---------------------------------------------------------------------------
    | Model Attributes
    |---------------------------------------------------------------------------
    */
    public function isExpired(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->expire < Carbon::now(),
        );
    }

    public function href(): Attribute
    {
        return Attribute::make(
            get: fn () => route('links.show', $this->link_id),
        );
    }

    public function publicHref(): Attribute
    {
        return Attribute::make(
            get: fn () => route('guest-link.show', $this->link_hash),
        );
    }

    public function createdStamp(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->created_at,
        );
    }

    /*
    |---------------------------------------------------------------------------
    | Model Relationships
    |---------------------------------------------------------------------------
    */
    public function Files(): BelongsToMany
    {
        return $this->belongsToMany(
            FileUpload::class,
            FileLinkFile::class,
            'link_id',
            'file_id',
        )->withPivot([
            'timeline_id',
            'upload',
            'link_file_id',
        ])->withTimestamps();
    }

    public function Uploads(): BelongsToMany
    {
        return $this->belongsToMany(
            FileUpload::class,
            FileLinkFile::class,
            'link_id',
            'file_id',
        )
            ->wherePivot('upload', true)
            ->withPivot([
                'timeline_id',
                'upload',
                'link_file_id',
                'created_at',
                'moved',
            ])
            ->withTimestamps();
    }

    public function Downloads(): BelongsToMany
    {
        return $this->belongsToMany(
            FileUpload::class,
            FileLinkFile::class,
            'link_id',
            'file_id',
        )
            ->wherePivot('upload', false)
            ->withPivot([
                'timeline_id',
                'upload',
                'link_file_id',
                'created_at',
                'moved',
            ])
            ->withTimestamps();
    }

    public function User(): HasOne
    {
        return $this->hasOne(User::class, 'user_id', 'user_id');
    }

    public function Timeline(): HasMany
    {
        return $this->hasMany(FileLinkTimeline::class, 'link_id', 'link_id');
    }

    public function Customer(): HasOne
    {
        return $this->hasOne(Customer::class, 'cust_id', 'cust_id');
    }

    /*
    |---------------------------------------------------------------------------
    | Additional Model Methods
    |---------------------------------------------------------------------------
    */

    /**
     * Verify that the link is valid
     */
    public function validatePublicLink(): bool
    {
        if (Carbon::parse($this->expire) < Carbon::now()) {
            throw new FileLinkExpiredException($this);
        }

        return true;
    }

    /*
    |---------------------------------------------------------------------------
    | Prune expired models after xx days
    |---------------------------------------------------------------------------
    */
    public function prunable(): Builder
    {
        Log::debug('Calling Prune File Links');

        if (config('file-link.auto_delete')) {
            $linkList = static::whereDate('expire', '<', now()
                ->subDays(config('file-link.auto_delete_days')));
            Log::debug('List of prunable File Links', $linkList->get()->toArray());

            if (! config('file-link.auto_delete_override')) {
                return $linkList;
            }

            $delList = [];
            $settingId = UserSettingType::where('name', 'Auto Delete Expired File Links')
                ->first()
                ->setting_type_id;

            Log::debug('Checking Each Link to see if user has overridden Auto Delete');
            foreach ($linkList->get() as $link) {
                $settingValue = UserSetting::where('user_id', $link->User->user_id)
                    ->where('setting_type_id', $settingId)
                    ->first()
                    ->value;

                Log::debug('User ID '.$link->User->user_id.' has Override Value set to '.(bool) $settingValue);
                if ((bool) $settingValue) {
                    $delList[] = $link->link_id;
                }
            }

            return static::whereIn('link_id', $delList);
        }

        return static::whereLinkId(0);
    }
}
