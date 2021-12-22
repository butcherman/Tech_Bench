<?php

namespace App\Http\Requests\Customers;

use Illuminate\Foundation\Http\FormRequest;

class CustomerSearchRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request
     */
    public function authorize()
    {
        return $this->user() ? true : false;
    }

    /**
     * Get the validation rules that apply to the request
     */
    public function rules()
    {
        return [
            'page'      => 'nullable|numeric',
            'perPage'   => 'required|numeric',
            'sortField' => 'required|string',
            'sortType'  => 'required|string',
            'name'      => 'nullable|string',
            'city'      => 'nullable|string',
            'equipment' => 'nullable|string',
        ];
    }
}
