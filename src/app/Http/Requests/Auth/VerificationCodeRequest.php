<?php

namespace App\Http\Requests\Auth;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class VerificationCodeRequest extends FormRequest
{
    protected $errorBag = 'form_error';

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'code' => ['required', 'string'],
            'remember' => ['required', 'boolean'],
        ];
    }

    /**
     * Validate the actual 2FA code
     */
    public function after(): array
    {
        return [
            function (Validator $validator) {
                $code = $this->user()->UserVerificationCode;

                // If code is missing or does not match
                if (! $code || $code->code !== $this->code) {
                    $validator->errors()
                        ->add('code', __('auth.2faFailed'));
                }

                // Code is only valid for 15 minutes
                if ($code->updated_at->addMinutes(15) < Carbon::now()) {
                    $validator->errors()
                        ->add('code', __('auth.2faExpired'));
                }
            },
        ];
    }
}
