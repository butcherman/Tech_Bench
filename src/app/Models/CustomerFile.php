<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Prunable;
use Illuminate\Database\Eloquent\SoftDeletes;

class CustomerFile extends Model
{
    use HasFactory;
    use Prunable;
    use SoftDeletes;

    protected $primaryKey = 'cust_file_id';

    protected $guarded = ['cust_file_id', 'created_at', 'updated_at'];

    protected $hidden = [
        'cust_id',
        'updated_at',
        'user_id',
        'deleted_at',
        'CustomerFileType',
        'CustomerEquipment',
        'user',
    ];

    protected $appends = [
        'uploaded_by',
        'file_type',
        'equip_name',
        'created_stamp',
    ];

    protected $with = ['CustomerSite'];

    protected $casts = [
        'created_at' => 'datetime:M d, Y',
        'updated_at' => 'datetime:M d, Y',
        'deleted_at' => 'datetime:M d, Y',
    ];

    /**
     * Each file is attached to a specific file entry
     */
    public function FileUpload()
    {
        return $this->hasOne(FileUpload::class, 'file_id', 'file_id');
    }

    public function CustomerSite()
    {
        return $this->belongsToMany(
            CustomerSite::class,
            'customer_site_files',
            'cust_file_id',
            'cust_site_id'
        );
    }

    public function CustomerFileType()
    {
        return $this->hasOne(CustomerFileType::class, 'file_type_id', 'file_type_id');
    }

    public function User()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id')->withTrashed();
    }

    public function CustomerEquipment()
    {
        return $this->belongsTo(CustomerEquipment::class, 'cust_equip_id', 'cust_equip_id');
    }

    public function getFileTypeAttribute()
    {
        return $this->CustomerFileType->description;
    }

    public function getCreatedStampAttribute()
    {
        return $this->created_at;
    }

    public function getHrefAttribute()
    {
        return route('download', [$this->file_id, $this->FileUpload->file_name]);
    }

    public function getUploadedByAttribute()
    {
        return $this->user->full_name;
    }

    public function getEquipNameAttribute()
    {
        return $this->CustomerEquipment ? $this->CustomerEquipment->equip_name : null;
    }

    /**
     * Automatically remove soft deleted models after 90 days
     */
    public function prunable()
    {
        if (config('customer.auto_purge')) {
            return static::where('deleted_at', '<=', now()->subDays(90));
        }

        return false;
    }
}
