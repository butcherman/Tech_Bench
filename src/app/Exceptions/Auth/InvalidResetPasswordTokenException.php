<?php

// TODO - Refactor

namespace App\Exceptions\Auth;

use App\Http\Requests\Auth\ResetPasswordRequest;
use Exception;
use Illuminate\Support\Facades\Log;

/**
 * Exception occurs when a visit to reset password route happens with an
 * invalid token ID.
 */
class InvalidResetPasswordTokenException extends Exception
{
    protected ResetPasswordRequest $requestData;

    public function __construct(ResetPasswordRequest $requestData)
    {
        parent::__construct();
        $this->requestData = $requestData;
    }

    public function report(): void
    {
        Log::alert('Someone tried to access a Reset Password link with an invalid Password Reset token', [
            'token' => $this->requestData->token,
            'email' => $this->requestData->email,
            'ip_address' => $this->requestData->ip(),
        ]);
    }

    public function render(): never
    {
        abort(404);
    }
}
