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
        ];
    }

    /**
     * Determine if the verification code is valid
     */
    public function verifyCode()
    {
        return UserCode::where('user_id', $this->user()->user_id)
            ->where('code', $this->code)
            ->first()
            ? true : false;
    }
}
