<?php

namespace App\Exceptions\Auth;

use Exception;
use Illuminate\Support\Facades\Log;
use SocialiteProviders\Azure\User;

class UnableToCreateAzureUserException extends Exception
{
    public function __construct(protected User $user)
    {
        parent::__construct();
    }

    public function report(): void
    {
        Log::alert('Azure User '.$this->user->name.' authenticated properly with Azure, but does not have a
                    local account.  An account must be created for them, or enable auto-creation in User
                    Administration Settings.');
    }

    public function render()
    {
        return redirect(route('login'))
            ->with('warning', 'You do not have permission to Login.  Please'.
                   ' contact your system administrator');
    }
}
