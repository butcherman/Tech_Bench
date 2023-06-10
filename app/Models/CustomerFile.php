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

    protected $hidden = ['cust_id', 'file_type_id', 'created_at', 'user_id', 'deleted_at'];

    protected $appends = ['uploaded_by', 'file_type'];

    protected $with = ['FileUpload'];

    protected $casts = [
        'created_at' => 'datetime:M d, Y',
        'updated_at' => 'datetime:M d, Y',
        'deleted_at' => 'datetime:M d, Y',
        'shared' => 'boolean',
    ];

    /**
     * Each file is attached to a specific file entry
     */
    public function FileUpload()
    {
        return $this->hasOne(FileUploads::class, 'file_id', 'file_id');
    }

    /**
     * Each file belongs to a customer
     */
    public function Customer()
    {
        return $this->belongsTo(Customer::class, 'cust_id', 'cust_id');
    }

    /**
     * Full name of the user that uploaded the file
     */
    public function getUploadedByAttribute()
    {
        return User::withTrashed()->find($this->user_id)->full_name;
    }

    /**
     * Type of file that was uploaded (i.e. Backup, Site Map, etc)
     */
    public function getFileTypeAttribute()
    {
        return CustomerFileType::find($this->file_type_id)->description;
    }

    /**
     * Return the soft deleted items re-formatted to match other models
     */
    public static function getTrashed(Customer $customer)
    {
        $data = self::where('cust_id', $customer->cust_id)
            ->onlyTrashed()
            ->get()
            ->map(function ($item) {
                return [
                    'item_id' => $item->cust_equip_id,
                    'item_name' => $item->name,
                    'item_deleted' => $item->deleted_at->toFormattedDateString(),
                ];
            });

        return $data;
    }
}
