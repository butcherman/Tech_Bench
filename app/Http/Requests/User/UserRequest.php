<?php

namespace App\Http\Requests\User;

use App\Models\User;

use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    /**
     * A user can update their own information, or an administrator with "Manage Users" permissions can do if for them
     */
    public function authorize()
    {
        //  On creating a new user, only an admin can submit the request
        if($this->route('setting') === null)
        {
            return $this->user()->can('create', User::class);
        }

        $user = User::find($this->route('setting'));
        return $user && $this->user()->can('update', $user);
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules()
    {
        return [
            'username'   => [
                Rule::unique('users')->ignore($this->route('setting'), 'user_id'),
                Rule::requiredIf(fn() => Route::current()->getName() === 'user.store'),
            ],
            'first_name' => 'required|string',
            'last_name'  => 'required|string',
            'email'      => [
                'required',
                'email',
                Rule::unique('users')->ignore($this->route('setting'), 'user_id'),
            ],
        ];
    }
}
