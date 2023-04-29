<?php

namespace App\Http\Requests\Customers;

use App\Models\CustomerFileType;
use Illuminate\Foundation\Http\FormRequest;

class CustomerFileTypeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request
     */
    public function authorize()
    {
        // return false;
        if ($this->file_type) {
            return $this->user()->can('update', $this->file_type);
        }

        return $this->user()->can('create', CustomerFileType::class);
    }

    /**
     * Get the validation rules that apply to the request
     */
    public function rules()
    {
        return [
            'description' => 'required|string',
        ];
    }
}
