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

    protected $guarded = ['note_id', 'updated_at', 'created_at'];

    protected $casts = [
        'created_at' => 'datetime:M d, Y',
        'updated_at' => 'datetime:M d, Y',
        'deleted_at' => 'datetime:M d, Y',
        'urgent' => 'boolean',
    ];

    public function CustomerSite()
    {
        return $this->belongsToMany(
            CustomerSite::class,
            'customer_site_notes',
            'note_id',
            'cust_site_id'
        );
    }
}
