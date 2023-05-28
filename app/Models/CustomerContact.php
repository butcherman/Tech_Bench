<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CustomerContact extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $primaryKey = 'cont_id';

    protected $guarded = ['cont_id', 'created_at', 'updated_at'];

    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];

    protected $with = ['CustomerContactPhone'];

    protected $casts = [
        'deleted_at' => 'datetime:M d, Y',
    ];

    /**
     * Each customer contact can have several phone numbers attached
     */
    public function CustomerContactPhone()
    {
        return $this->hasMany(CustomerContactPhone::class, 'cont_id', 'cont_id');
    }

    /**
     * Each Contact is assigned to a customer
     */
    public function Customer()
    {
        return $this->belongsTo(Customer::class, 'cust_id', 'cust_id');
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
                    'item_id' => $item->cont_id,
                    'item_name' => $item->name,
                    'item_deleted' => $item->deleted_at->toFormattedDateString(),
                ];
            });

        return $data;
    }
}
