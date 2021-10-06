<?php

namespace App\Http\Requests\User;

use App\Models\User;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // return false;
        if($this->route('user'))
        {
            return $this->user()->can('update', User::find($this->route('user')));
        }

        return $this->user()->can('create', User::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            // 'username'   => 'required|unique:users',
            // 'first_name' => 'required|string',
            // 'last_name'  => 'required|string',
            // 'email'      => 'required|email|unique:users,except,'.$this->route('user'),
            // 'role_id'    => 'required|exists:user_roles',

            'username'   => [
                'required',
                Rule::unique('users')->ignore($this->route('user'), 'user_id'),
                // Rule::requiredIf(fn() => Route::current()->getName() === 'user.store'),
            ],
            'first_name' => 'required|string',
            'last_name'  => 'required|string',
            'email'      => [
                'required',
                'email',
                Rule::unique('users')->ignore($this->route('user'), 'user_id'),
            ],
            'role_id'    => 'required|exists:user_roles',
        ];
    }
}
