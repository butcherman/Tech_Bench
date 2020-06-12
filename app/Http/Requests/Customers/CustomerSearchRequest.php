<?php

namespace App\Http\Requests\Customers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

class CustomerSearchRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
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
            'equipment' => 'nullable|numeric',
        ];
    }
}
