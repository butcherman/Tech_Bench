<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customers extends Model
{
    use SoftDeletes;

    protected $primaryKey = 'cust_id';
    protected $fillable   = ['cust_id', 'parent_id', 'name', 'dba_name', 'address', 'city', 'state', 'zip', 'active'];
    protected $hidden     = ['created_at', 'deleted_at', 'updated_at'];
    protected $appends    = ['child_count'];
    protected $casts      = [
        'deleted_at' => 'datetime:M d, Y',
    ];

    public function CustomerSystems()
    {
        return $this->hasMany('App\CustomerSystems', 'cust_id', 'cust_id');
    }

    public function getChildCountAttribute()
    {
        return Customers::where('parent_id', $this->cust_id)->count();
    }
}
