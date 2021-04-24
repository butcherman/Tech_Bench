<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerContact extends Model
{
    use HasFactory;

    protected $primaryKey = 'cont_id';
    protected $guarded    = ['cont_id', 'created_at', 'updated_at'];
    protected $hidden     = ['created_at', 'updated_at'];

    public function CustomerContactPhone()
    {
        return $this->hasMany(CustomerContactPhone::class, 'cont_id', 'cont_id');
    }
}
