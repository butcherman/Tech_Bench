<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

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
            'password' => 'required|min:6|confirmed',
        ];
    }
}
