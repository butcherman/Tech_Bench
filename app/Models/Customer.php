<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Customer extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $primaryKey = 'cust_id';
    protected $guarded    = ['updated_at', 'created_at', 'deleted_at'];
    protected $hidden     = ['updated_at', 'created_at', 'deleted_at'];
    protected $appends    = ['child_count'];

    /*
    *   If a customer is part of a multi-site customer, each site can be listed separately yet still be linked to the main site
    */
    public function Parent()
    {
        return $this->belongsTo(Customer::class, 'parent_id', 'cust_id');
    }

    /*
    *   If a customer is the parent and has children below it, they will be counted
    */
    public function getChildCountAttribute()
    {
        return Customer::where('parent_id', $this->cust_id)->count();
    }

    /*
    *   Several equipment types can be assigned to a customer
    */
    public function EquipmentType()
    {
        return $this->hasManyThrough('App\Models\EquipmentType', 'App\Models\CustomerEquipment', 'cust_id', 'equip_id', 'cust_id', 'equip_id');
    }

    /*
    *   Equipment that is assigned to a customer will be listed
    */
    public function CustomerEquipment()
    {
        return $this->hasMany(CustomerEquipment::class, 'cust_id', 'cust_id');
    }

    //  TODO - ParentEquipmentType()

    /*
    *   Equipment that is shared and belong to the parent site will show up for this customer
    */
    public function ParentEquipment()
    {
        //  TODO - verify only shared equipment make it through
        return $this->hasMany(CustomerEquipment::class, 'cust_id', 'parent_id')->whereShared(true); // ->where('shared', true);
    }

        /*
    *   Site and other contacts for the customer
    */
    public function CustomerContact()
    {
        return $this->hasMany(CustomerContact::class, 'cust_id', 'cust_id');
    }

    /*
    *   Shared contacts throughout all linked sites
    */
    public function ParentContact()
    {
        return $this->hasMany(CustomerContact::class, 'cust_id', 'parent_id')->where('shared', true);
    }

    /*
    *   Site Specific notes for this customer
    */
    public function CustomerNote()
    {
        return $this->hasMany(CustomerNote::class, 'cust_id', 'cust_id');
    }

    /*
    *   Shared notes throughout all sites
    */
    //  TODO - Parent Notes

    /*
    *   Files attached to this customer
    */
    public function CustomerFile()
    {
        return $this->hasMany(CustomerFile::class, 'cust_id', 'cust_id');
    }

    /*
    *   Files that are shared throughout all linked sites
    */
    public function ParentFile()
    {
        return $this->hasMany(CustomerFile::class, 'cust_id', 'parent_id')->where('shared', true);
    }
}
