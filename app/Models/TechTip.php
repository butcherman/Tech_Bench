<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TechTip extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $primaryKey = 'tip_id';
    protected $guarded    = ['tip_id', 'created_at', 'updated_at'];
    protected $hidden     = ['deleted_at', 'tip_type_id', 'updated_id', 'user_id'];
    protected $appends    = ['summary'];
    protected $casts      = [
        'created_at' => 'datetime:M d, Y',
        'updated_at' => 'datetime:M d, Y',
        'deleted_at' => 'datetime:M d, Y',
    ];

    public function getSummaryAttribute()
    {
        return Str::words($this->details, 50);
    }

    public function EquipmentType()
    {
        return $this->hasManyThrough(EquipmentType::class, TechTipEquipment::class, 'tip_id', 'equip_id', 'tip_id', 'equip_id');
    }

    public function FileUploads()
    {
        return $this->hasManyThrough(FileUploads::class, TechTipFile::class, 'tip_id', 'file_id', 'tip_id', 'file_id');
    }

    public function TechTipComment()
    {
        return $this->hasMany(TechTipComment::class, 'tip_id', 'tip_id');
    }

    public function CreatedBy()
    {
        return $this->hasOne(User::class, 'user_id', 'user_id');
    }

    public function UpdatedBy()
    {
        return $this->hasOne(User::class, 'user_id', 'updated_id');
    }
}
