<?php

namespace App\Http\Requests\Auth;

use App\Models\UserVerificationCode;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class VerificationCodeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user() ? true : false;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'code' => 'required|string',
            'remember' => 'required|boolean',
        ];
    }

    /**
     * Determine if the verification code is valid
     */
    public function after()
    {
        return [
            function (Validator $validator) {
                $code = UserVerificationCode::where('user_id', $this->user()->user_id)
                    ->whereCode($this->code)
                    ->count();

                if (! $code) {
                    $validator->errors()
                        ->add('code', 'The provided code is incorrect');
                }
            },
        ];
    }
}
