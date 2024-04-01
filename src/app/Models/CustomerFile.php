<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CustomerFile extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $primaryKey = 'cust_file_id';

    protected $guarded = ['cust_file_id', 'created_at', 'updated_at'];

    protected $hidden = [
        'cust_id',
        'updated_at',
        'user_id',
        'deleted_at',
    ];

    // protected $appends = [
    //     'uploaded_by',
    //     'file_type',
    //     'equip_name',
    //     'created_stamp',
    // ];

    // protected $with = ['FileUpload'];

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
}
