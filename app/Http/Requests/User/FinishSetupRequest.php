<?php

namespace App\Http\Requests\User;

use App\Models\UserInitialize;
use Illuminate\Foundation\Http\FormRequest;

class FinishSetupRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request
     */
    public function authorize()
    {
        //  Verify that the token is valid
        $token = UserInitialize::where('token', $this->route()->token)->count();
        return $token == 1 ? true : false;
    }

    /**
     * Get the validation rules that apply to the request
     */
    public function rules()
    {
        return [
            'username' => 'required|exists:user_initializes',
            'password' => 'required|min:6|confirmed',
        ];
    }
}
