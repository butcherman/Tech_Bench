<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class TechTip extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $primaryKey = 'tip_id';

    protected $guarded = ['tip_id', 'created_at', 'updated_at'];

    protected $hidden = ['deleted_at', 'tip_type_id', 'updated_id', 'user_id'];

    // protected $appends = ['summary'];

    protected $casts = [
        'created_at' => 'datetime:M d, Y',
        'updated_at' => 'datetime:M d, Y',
        'deleted_at' => 'datetime:M d, Y',
        'sticky' => 'boolean',
    ];

    /**
     * Shortened version of the details column for a brief view
     */
    // public function getSummaryAttribute()
    // {
    //     return Str::words($this->details, 50);
    // }

    /**
     * Each Tech Tip can have several equipment types assigned
     */
    // public function EquipmentType()
    // {
    //     return $this->hasManyThrough(EquipmentType::class, TechTipEquipment::class, 'tip_id', 'equip_id', 'tip_id', 'equip_id');
    // }

    /**
     * Each Tech Tip can have several files assigned
     */
    // public function FileUploads()
    // {
    //     return $this->hasManyThrough(FileUploads::class, TechTipFile::class, 'tip_id', 'file_id', 'tip_id', 'file_id');
    // }

    /**
     * Each Tech Tip can have several comments
     */
    // public function TechTipComment()
    // {
    //     return $this->hasMany(TechTipComment::class, 'tip_id', 'tip_id');
    // }

    /**
     * Each Tech Tip has a user that created it
     */
    // public function CreatedBy()
    // {
    //     return $this->hasOne(User::class, 'user_id', 'user_id');
    // }

    /**
     * Each Tech Tip can be modified by one user
     * If it has been modified several times, the last user to modify it is the one recorded
     */
    // public function UpdatedBy()
    // {
    //     return $this->hasOne(User::class, 'user_id', 'updated_id');
    // }
}
