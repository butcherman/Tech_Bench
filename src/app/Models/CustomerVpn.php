<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerVpn extends Model
{
    protected $primaryKey = 'vpn_id';

    protected $guarded = ['vpn_id', 'created_at', 'updated_at'];
}
