<?php

namespace App\Exceptions\Auth;

use App\Http\Requests\Auth\ResetPasswordRequest;
use Exception;
use Illuminate\Support\Facades\Log;

class InvalidResetPasswordTokenException extends Exception
{
    protected ResetPasswordRequest $requestData;

    public function __construct(ResetPasswordRequest $requestData)
    {
        parent::__construct();
        $this->requestData = $requestData;
    }

    public function report()
    {
        Log::alert('Someone tried to access a Reset Password link with an invalid Password Reset token', [
            'token' => $this->requestData->token,
            'email' => $this->requestData->email,
            'ip_address' => $this->requestData->ip(),
        ]);
    }

    public function render()
    {
        abort(404);
    }
}
