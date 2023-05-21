<?php

namespace App\Http\Requests\Customers;

use App\Models\CustomerFile;
use Illuminate\Foundation\Http\FormRequest;

class CustomerFileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        if($this->customerFile)
        {
            return $this->user()->can('update', $this->customerFile);
        }

        return $this->user()->can('create', CustomerFile::class);
    }

    /**
     * Get the validation rules that apply to the request
     */
    public function rules(): array
    {
        return [
            'cust_id' => 'required|exists:customers',
            'name' => 'required|string',
            'type' => 'required|string',
            'shared' => 'required|string',
        ];
    }
}
