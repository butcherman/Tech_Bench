<?php

namespace App\Domains\Users;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

use App\User;

class UserList
{
    public function getActiveUsers()
    {
        return User::with('LastUserLogin')->get()->makeVisible(('user_id'));
    }
}
