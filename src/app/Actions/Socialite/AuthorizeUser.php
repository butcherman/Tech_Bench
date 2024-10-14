<?php

namespace App\Actions\Socialite;

use App\Exceptions\Auth\UnableToCreateAzureUserException;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;
use SocialiteProviders\Azure\User as AzureUser;

/**
 * Log user in via Office 365 Integration
 */
class AuthorizeUser
{
    /**
     * Attempt to authenticate azure user
     */
    public function handle(): User
    {
        $azureUser = Socialite::driver('azure')->user();
        $localUser = $this->getLocalUser($azureUser);

        if (! $localUser) {
            $localUser = $this->processNewUser($azureUser);
        }

        Auth::login($localUser, true);

        $this->bypass2fa();

        Log::info('User '.$localUser->username.' logged in via Microsoft Azure');

        return $localUser;
    }

    /**
     * Get the local user from the database if they exist
     */
    protected function getLocalUser(AzureUser $azureUser): User|bool
    {
        $user = User::where('email', $azureUser->mail)->first();

        return $user ?? false;
    }

    /**
     * Create a new user (if allowed) and authenticate this user
     */
    protected function processNewUser(AzureUser $azureUser): User
    {
        if (! config('services.azure.allow_register')) {
            throw (new UnableToCreateAzureUserException($azureUser));
        }

        return $this->buildUser($azureUser);
    }

    /**
     * Create the new user in the database
     */
    protected function buildUser(AzureUser $socUser): User
    {
        $newUser = User::create([
            'role_id' => config('services.azure.default_role_id'),
            'username' => $socUser->user['userPrincipalName'],
            'first_name' => $socUser->user['givenName'],
            'last_name' => $socUser->user['surname'],
            'email' => $socUser->user['mail'],
            'password' => Hash::make($socUser->user['id']),
        ]);

        return $newUser;
    }

    /**
     * Determine if the user can bypass 2FA and set necessary session
     */
    protected function bypass2fa(): void
    {
        if (config('services.azure.allow_bypass_2fa')) {
            session()->put('2fa_verified', true);
        }
    }
}
