<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\ContainsNumber;
use App\Rules\ContainsLowerCase;
use App\Rules\ContainsUpperCase;
use App\Rules\ContainsSpecialChar;

class FinishSetupRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request
     */
    public function authorize()
    {
        return $this->initLink !== null;
    }

    /**
     * Get the validation rules that apply to the request
     */
    public function rules()
    {
        return [
            'password' => [
                'required',
                'min:'.config('auth.passwords.settings.min_length'),
                'confirmed',
                'different:current_password',
                new ContainsLowerCase,
                new ContainsUpperCase,
                new ContainsNumber,
                new ContainsSpecialChar,
            ]
        ];
    }
}
