<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Prunable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Log;

class CustomerNote extends Model
{
    use HasFactory;
    use Prunable;
    use SoftDeletes;

    protected $primaryKey = 'note_id';

    protected $guarded = ['note_id', 'updated_at', 'created_at'];

    protected $appends = ['author', 'updated_author'];

    protected $with = ['CustomerEquipment'];

    protected $casts = [
        'created_at' => 'datetime:M d, Y',
        'updated_at' => 'datetime:M d, Y',
        'deleted_at' => 'datetime:M d, Y',
        'urgent' => 'boolean',
    ];

    /***************************************************************************
     * Model Attributes
     ***************************************************************************/
    public function getAuthorAttribute()
    {
        return User::withTrashed()->find($this->created_by)->full_name ?? 'unknown';
    }

    public function getUpdatedAuthorAttribute()
    {
        if ($this->updated_by) {
            return User::withTrashed()->find($this->updated_by)->full_name ?? 'unknown';
        }
    }

    /***************************************************************************
     * Model Relationships
     ***************************************************************************/
    public function CustomerSite()
    {
        return $this->belongsToMany(
            CustomerSite::class,
            'customer_site_notes',
            'note_id',
            'cust_site_id'
        );
    }

    public function CustomerEquipment()
    {
        return $this->hasOne(
            CustomerEquipment::class,
            'cust_equip_id',
            'cust_equip_id'
        );
    }

    /***************************************************************************
     * Prune soft deleted models after 90 days
     ***************************************************************************/
    public function prunable()
    {
        Log::debug('Calling Prune Customer Notes');

        if (config('customer.auto_purge')) {
            return static::whereDate('deleted_at', '<=', now()->subDays(90));
        }

        return static::whereNoteId(0);
    }
}
