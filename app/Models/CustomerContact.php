<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CustomerContact extends Model
{
    use HasFactory;

    protected $primaryKey = 'cont_id';
    protected $guarded    = ['cont_id', 'created_at', 'updated_at'];
    protected $hidden     = ['created_at', 'updated_at'];

    /*
    *   Each customer contact can have several phone numbers attached
    */
    public function CustomerContactPhone()
    {
        return $this->hasMany(CustomerContactPhone::class, 'cont_id', 'cont_id');
    }
}
