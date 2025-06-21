<?php

namespace App\Traits\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait HasUser
{
    /**
     * Add User Relationship to the Model.
     */
    public function User(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id')
            ->withTrashed();
    }
}
