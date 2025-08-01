<?php

namespace App\Http\Requests\Admin\Misc;

use App\Models\Customer;
use Illuminate\Foundation\Http\FormRequest;

class ContactPhoneTypesRequest extends FormRequest
{
    protected $errorBag = 'form_error';

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('manage', Customer::class);
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'description' => ['required', 'string'],
            'icon_class' => ['required', 'string'],
        ];
    }
}
