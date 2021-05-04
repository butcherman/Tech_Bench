<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerFile extends Model
{
    use HasFactory;

    protected $primaryKey = 'cust_file_id';
    protected $guarded    = ['cust_file_id', 'created_at', 'updated_at'];
    protected $hidden     = ['cust_id', 'file_type_id', 'created_at', 'user_id'];
    protected $appends    = ['uploaded_by', 'file_type'];
    protected $casts      = [
        'created_at' => 'datetime:M d, Y',
        'updated_at' => 'datetime:M d, Y',
        'shared'     => 'boolean',
    ];


    public function FileUpload()
    {
        return $this->hasOne(FileUploads::class, 'file_id', 'file_id');
    }

    public function getUploadedByAttribute()
    {
        return User::find($this->user_id)->full_name;
    }

    public function getFileTypeAttribute()
    {
        return CustomerFileType::find($this->file_type_id)->description;
    }
}
