<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;

class FileLinkTimeline extends Model
{
    /** @var string */
    protected $primaryKey = 'timeline_id';

    /** @var array<int, string> */
    protected $guarded = ['timeline_id', 'updated_at'];

    /** @var array<int, string> */
    protected $hidden = ['updated_at'];

    /** @var array<int, string> */
    protected $with = ['Files', 'Notes'];

    /*
    |---------------------------------------------------------------------------
    | Model Casting
    |---------------------------------------------------------------------------
    */
    public function casts(): array
    {
        return [
            'created_at' => 'datetime:M d, Y h:i A',
            'updated_at' => 'datetime:M d, Y',
        ];
    }

    /*
    |---------------------------------------------------------------------------
    | Model Relationships
    |---------------------------------------------------------------------------
    */
    public function Files(): HasManyThrough
    {
        return $this->hasManyThrough(
            FileUpload::class,
            FileLinkFile::class,
            'timeline_id',
            'file_id',
            'timeline_id',
            'file_id',
        );
    }

    public function Notes(): HasOne
    {
        return $this->hasOne(FileLinkNote::class, 'timeline_id', 'timeline_id');
    }
}
