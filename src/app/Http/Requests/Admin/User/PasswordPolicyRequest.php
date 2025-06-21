<?php

namespace App\Http\Requests\Admin\User;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class PasswordPolicyRequest extends FormRequest
{
    protected $errorBag = 'form_error';

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('manage', User::class);
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'expire' => ['required', 'numeric'],
            'min_length' => ['required', 'numeric'],
            'contains_uppercase' => ['required', 'boolean'],
            'contains_lowercase' => ['required', 'boolean'],
            'contains_number' => ['required', 'boolean'],
            'contains_special' => ['required', 'boolean'],
            'disable_compromised' => ['required', 'boolean'],
        ];
    }
}
