<?php

namespace App\Http\Requests\User;

use App\Models\User;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize()
    {
        return $this->user()->can('update', User::find($this->route('setting')));
    }

    /**
     * Get the validation rules that apply to the request
     */
    public function rules()
    {
        return [
            'first_name' => 'required|string',
            'last_name'  => 'required|string',
            'email'      => [
                'required',
                'email',
                Rule::unique('users')->ignore($this->user()->user_id, 'user_id'),
            ]
        ];
    }
}
