<?php

namespace App\Http\Requests\User;

use App\Models\User;

use Illuminate\Foundation\Http\FormRequest;

class PasswordRequest extends FormRequest
{
    /**
     *  A user can change their own password, or an admin with "Manage Users" permissions can do it for them
     */
    public function authorize()
    {
        $user = User::where('username', $this->route('id'))->first();
        return $user && $this->user()->can('update', $user);
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules()
    {
        return [
            'password' => 'required|string|min:6|confirmed',
        ];
    }
}
