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

    protected $appends = ['author', 'updated_author'];

    protected $hidden = ['created_by', 'updated_by', 'deleted_at'];

    protected $casts = [
        'created_at' => 'datetime:M d, Y',
        'updated_at' => 'datetime:M d, Y',
        'deleted_at' => 'datetime:M d, Y',
        'urgent' => 'boolean',
        'shared' => 'boolean',
    ];

    public function Customer()
    {
        return $this->belongsTo(Customer::class, 'cust_id', 'cust_id');
    }

    /**
     * Name of the user who created the note
     *
     * @codeCoverageIgnore
     */
    public function getAuthorAttribute()
    {
        return User::withTrashed()->find($this->created_by)->full_name;
    }

    /**
     * Name of the user who most recently updated the note
     *
     * @codeCoverageIgnore
     */
    public function getUpdatedAuthorAttribute()
    {
        $user = User::withTrashed()->find($this->updated_by);

        return $user ? $user->full_name : null;
    }
}
