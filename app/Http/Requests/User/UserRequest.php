<?php

namespace App\Http\Requests\User;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request
     */
    public function authorize()
    {
        if ($this->user) {
            return $this->user()->can('update', $this->user);
        }

        return $this->user()->can('create', User::class);
    }

    /**
     * Get the validation rules that apply to the request
     */
    public function rules()
    {
        return [
            'username' => [
                'required',
                Rule::unique('users')->ignore($this->route('user'), 'user_id'),
            ],
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'email' => [
                'required',
                'email',
                Rule::unique('users')->ignore($this->route('user'), 'user_id'),
            ],
            'role_id' => 'required|exists:user_roles',
        ];
    }
}
