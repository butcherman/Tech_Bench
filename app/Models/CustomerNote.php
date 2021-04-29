<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerNote extends Model
{
    use HasFactory;

    protected $primaryKey = 'note_id';
    protected $guarded    = ['updated_at', 'created_at'];
    protected $appends    = ['author', 'updated_author'];
    protected $hidden     = ['created_by', 'updated_by'];
    protected $casts      = [
        'created_at' => 'datetime:M d, Y',
        'updated_at' => 'datetime:M d, Y',
        'urgent'     => 'boolean',
        'shared'     => 'boolean',
    ];

    public function getAuthorAttribute()
    {
        return User::find($this->created_by)->full_name;
    }

    public function getUpdatedAuthorAttribute()
    {
        $user = User::find($this->updated_by);
        return $user ? $user->full_name : null;
    }
}
