<?php

namespace App\Http\Requests\Customer;

use Illuminate\Foundation\Http\FormRequest;

class WorkbookValueRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        if ($this->public) {
            return true;
        }

        return $this->user() ? true : false;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'value' => ['nullable'],
            'index' => ['required_if:isTable,false', 'string'],
            'public' => ['required', 'boolean'],
            'isTable' => ['required', 'boolean'],
            'table_index' => ['required_if:isTable,true', 'string'],
            'row_index' => ['required_if:isTable,true', 'numeric'],
            'column_name' => ['required_if:isTable,true', 'string'],
        ];
    }
}
