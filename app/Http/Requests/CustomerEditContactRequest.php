<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CustomerEditContactRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'cont_id'                 => 'required',
            'cust_id'                 => 'required',
            'name'                    => 'required',
            'shared'                  => 'required',
            'email'                   => 'nullable',
            'customer_contact_phones' => 'nullable',
        ];
    }
}
