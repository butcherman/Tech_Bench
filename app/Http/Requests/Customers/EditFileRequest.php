<?php

namespace App\Http\Requests\Customers;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class EditFileRequest extends FormRequest
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
            'cust_id'      => 'required|exists:customers,cust_id',
            'name'         => 'required|string',
            'file_type_id' => 'required|exists:customer_file_types,file_type_id',
            'shared'       => 'required|boolean',
        ];
    }
}
