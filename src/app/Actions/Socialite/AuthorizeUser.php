<?php

namespace App\Actions\Socialite;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

/**
 * Log user in via Office 365 Integration
 *
 * @codeCoverageIgnore
 */
class AuthorizeUser
{
    protected $user;

    /**
     * Try to authenticate the new user
     */
    public function processUser($socUser)
    {
        if ($this->doesUserExist($socUser)) {
            Auth::login($this->user, true);
            $this->canBypassTwoFa();
            Log::stack(['user', 'auth'])
                ->info('User '.$this->user->username.' logged in via Microsoft Azure');

            return $this->user;
        }

        //  If the user does not exist, determine if we can create them
        if (config('services.azure.allow_register')) {
            $this->buildUser($socUser);
            Auth::login($this->user, true);
            $this->canBypassTwoFa();
            Log::stack(['user', 'auth'])
                ->info('User '.$this->user->username.' logged in via Microsoft Azure');

            return $this->user;
        }

        Log::stack(['user', 'auth'])->info('User '.$socUser->user['userPrincipalName'].
            ' tried to login via Microsoft Azure, but failed because this user does not'.
            ' exist in the database.');

        return false;
    }

    /**
     * Determine if the user already exists in the database
     */
    protected function doesUserExist($socUser)
    {
        $user = User::where('email', $socUser->email)->first();
        if ($user) {
            $this->user = $user;

            return true;
        }

        return false;
    }

    /**
     * Create the new user in the database
     */
    protected function buildUser($socUser)
    {
        $newUser = User::create([
            'role_id' => config('services.azure.default_role_id'),
            'username' => $socUser->user['userPrincipalName'],
            'first_name' => $socUser->user['givenName'],
            'last_name' => $socUser->user['surname'],
            'email' => $socUser->user['mail'],
            'password' => Hash::make($socUser->user['id']),
        ]);

        $this->user = $newUser;
        Log::channel('user')
            ->info('New User '.$this->user->username.' created via Microsoft Azure driver');
    }

    /**
     * Determine if the user can bypass 2FA and set necessary session
     */
    protected function canBypassTwoFa()
    {
        if (config('services.azure.allow_bypass_2fa')) {
            session()->put('2fa_verified', true);
        }
    }
}
