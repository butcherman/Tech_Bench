<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthenticateUser
{
    public function authenticate(Request $request): ?User
    {
        $user = User::where('email', $request->username)
            ->orWhere('username', $request->username)
            ->first();

        if ($user && Hash::check($request->password, $user->password)) {
            return $user;
        }

        return null;
    }
}
