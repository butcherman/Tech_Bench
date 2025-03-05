<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserAccountRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->user);
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'first_name' => ['required', 'string'],
            'last_name' => ['required', 'string'],
            'email' => [
                'required',
                'email',
                Rule::unique('users')
                    ->ignore($this->user()->user_id, 'user_id'),
            ],
        ];
    }
}
