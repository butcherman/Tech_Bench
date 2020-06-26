<?php

namespace App\Domains\User;

use Illuminate\Support\Facades\Log;

use App\User;

class GetUserList
{
    //  Get all active users that are able to log in
    public function getActiveUsers()
    {
        return User::with('LastUserLogin')->get()->makeVisible('user_id');
    }

    //  Get all disabled users
    public function getInactiveUsers()
    {
        return User::onlyTrashed()->get()->makeVisible(['user_id', 'deleted_at']);
    }
}
