<?php

namespace App\Http\Requests\User;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\Rule;

class UserRequest extends FormRequest
{
    // protected $usernameRequiredRoutes = [
    //     'user.store',
    // ];

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if($this->route('setting') === null)
        {
            return $this->user()->can('create', User::class);
        }

        $user = User::find($this->route('setting'));
        return $user && $this->user()->can('update', $user);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        // dd($this->route());
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
