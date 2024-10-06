<?php

namespace App\Models;

use App\Observers\FileLinkObserver;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Prunable;
use Illuminate\Support\Facades\Log;

#[ObservedBy([FileLinkObserver::class])]
class FileLink extends Model
{
    use HasFactory;
    use Prunable;

    protected $primaryKey = 'link_id';

    protected $guarded = ['link_id', 'created_at', 'updated_at'];

    protected $hidden = ['user_id', 'created_at', 'updated_at'];

    protected $appends = ['is_expired', 'href', 'public_href', 'created_stamp'];

    protected $casts = [
        'allow_upload' => 'boolean',
        'created_at' => 'datetime:M d, Y',
        'updated_at' => 'datetime:M d, Y',
        'expire' => 'datetime:M d, Y',
    ];

    /***************************************************************************
     * Model Attributes
     ***************************************************************************/
    public function getIsExpiredAttribute()
    {
        return $this->expire < Carbon::now();
    }

    public function getHrefAttribute()
    {
        return route('links.show', $this->link_id);
    }

    public function getPublicHrefAttribute()
    {
        return route('guest-link.show', $this->link_hash);
    }

    public function getCreatedStampAttribute()
    {
        return $this->created_at;
    }

    /***************************************************************************
     * Model Relationships
     ***************************************************************************/
    public function FileUpload()
    {
        return $this->belongsToMany(
            FileUpload::class,
            'file_link_files',
            'link_id',
            'file_id',
        )->withPivot([
            'timeline_id',
            'upload',
            'link_file_id',
        ])->withTimestamps();
    }

    public function User()
    {
        return $this->hasOne(User::class, 'user_id', 'user_id');
    }

    public function Timeline()
    {
        return $this->hasMany(FileLinkTimeline::class, 'link_id', 'link_id');
    }

    /***************************************************************************
     * Additional Model Methods
     ***************************************************************************/
    public function expireLink()
    {
        $this->update([
            'expire' => Carbon::yesterday(),
        ]);
    }

    /***************************************************************************
     * Prune expired models after xx days
     ***************************************************************************/
    public function prunable()
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
