<?php

namespace App\Http\Requests\Home;

use App\Models\Customer;
use Illuminate\Foundation\Http\FormRequest;

class FileTypesRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('manage', Customer::class);
    }

    /**
     * Get the validation rules that apply to the request
     */
    public function rules(): array
    {
        return [
            'description' => ['required'],
        ];
    }
}
