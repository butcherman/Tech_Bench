<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CustomerNotes extends Model
{
    protected $primaryKey = 'note_id';
    protected $fillable   = ['cust_id', 'user_id', 'urgent', 'shared', 'subject', 'description'];
}
