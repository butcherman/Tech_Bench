<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FileLink extends Model
{
    use HasFactory;

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

    public function FileUpload()
    {
        return $this->belongsToMany(
            FileUpload::class,
            'file_link_files',
            'link_id',
            'file_id',
        )->withPivot([
                    'added_by',
                    'upload'
                ])->withTimestamps();
    }

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

    public function expireLink()
    {
        $this->update([
            'expire' => Carbon::yesterday(),
        ]);
    }
}
