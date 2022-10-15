<?php

namespace App\Http\Requests\User;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class ChangeUserPasswordRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request
     */
    public function authorize()
    {
        return $this->user()->can('update', $this->user);
    }

    /**
     * Get the validation rules that apply to the request
     */
    public function rules()
    {
        return [
            'password'  => 'required|min:6|confirmed',
        ];
    }
}
