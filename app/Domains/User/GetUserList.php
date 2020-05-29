<?php

namespace App\Domains\User;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

use App\User;


class GetUserList
{
    public function getActiveUsers()
    {
        return User::with('LastUserLogin')->get()->makeVisible('user_id');
    }

    public function getInactiveUsers()
    {
        return User::onlyTrashed()->get()->makeVisible(['user_id', 'deleted_at']);
    }
}
