<?php

namespace App\Exceptions\Auth;

use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use SocialiteProviders\Azure\User;

/*
|-------------------------------------------------------------------------------
| Exception notes that a user valid to the Azure Database tried to log in,
| but this user does not currently have a Tech Bench account and the
| system configuration does not allow the auto creation of accounts.
|-------------------------------------------------------------------------------
*/

class UnableToCreateSocialiteUserException extends Exception
{
    /**
     * @codeCoverageIgnore
     */
    public function __construct(protected User $user)
    {
        parent::__construct();
    }

    public function report(): void
    {
        Log::alert(
            'Azure User '.$this->user->name.' authenticated properly with Azure,
             but does not have a local account.  An account must be created for
             them, or enable auto-creation in UserAdministration Settings.'
        );
    }

    public function render(): RedirectResponse
    {
        return redirect(route('login'))
            ->with('warning', 'You do not have permission to Login.  Please'.
                ' contact your system administrator');
    }
}
