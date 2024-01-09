<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CustomerNote extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $primaryKey = 'note_id';

    protected $guarded = ['updated_at', 'created_at'];

    protected $hidden = [
        'created_by',
        'updated_by',
        'deleted_at',
        'EquipmentType',
        'CustomerNoteSite',
    ];

    protected $casts = [
        'created_at' => 'datetime:M d, Y',
        'updated_at' => 'datetime:M d, Y',
        'deleted_at' => 'datetime:M d, Y',
        'urgent' => 'boolean',
    ];
}
