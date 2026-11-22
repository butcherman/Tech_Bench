<?php

namespace App\Services\User;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;

class UserAdministrationService
{
    /**
     * Return a list of active, or deactivated users
     */
    public function getAllUsers(?bool $trashed = false): EloquentCollection
    {
        if ($trashed) {
            return User::onlyTrashed()->get()->makeVisible('deleted_at');
        }

        return User::all();
    }
}
