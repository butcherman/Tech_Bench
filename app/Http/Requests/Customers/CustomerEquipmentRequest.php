<?php

namespace App\Http\Requests\Customers;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class CustomerEquipmentRequest extends FormRequest
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
            'sys_id'  => 'required|exists:system_types,sys_id',
            'cust_id' => 'required|exists:customers,cust_id',
            'shared'  => 'required|boolean',
            'fields'  => 'required|array',
        ];
    }
}
