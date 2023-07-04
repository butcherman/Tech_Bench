<?php

namespace App\Http\Requests\Auth;

use App\Models\UserCode;
use Illuminate\Foundation\Http\FormRequest;

class VerificationCodeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request
     */
    public function authorize(): bool
    {
        return $this->user() ? true : false;
    }

    /**
     * Get the validation rules that apply to the request
     */
    public function rules(): array
    {
        return [
            'code' => 'required',
            'remember' => 'nullable|boolean',
        ];
    }

    /**
     * Determine if the verification code is valid
     */
    public function verifyCode()
    {
        $code = UserCode::where('user_id', $this->user()->user_id)
            ->where('code', $this->code)
            ->first();

        //  Does code exist?
        if (! $code) {
            return false;
        }

        //  Is the "Remember Device" flag set
        if ($this->remember) {
            $token = $this->user()->generateRememberDeviceToken();

            return $token;
        }

        return true;
    }
}
