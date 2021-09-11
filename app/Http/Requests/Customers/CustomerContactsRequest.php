<?php

namespace App\Http\Requests\Customers;

use App\Models\CustomerContact;
use Illuminate\Foundation\Http\FormRequest;

class CustomerContactsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request
     */
    public function authorize()
    {
        if($this->route('contact'))
        {
            return $this->user()->can('update', CustomerContact::find($this->route('contact')));
        }

        return $this->user()->can('create', CustomerContact::class);
    }

    /**
     * Get the validation rules that apply to the request
     */
    public function rules()
    {
        return [
            'cust_id' => 'required|exists:customers',
            'name'    => 'required|string',
            'title'   => 'nullable|string',
            'email'   => 'nullable|email',
            'note'    => 'nullable|string',
            'shared'  => 'required|boolean',
            'phones'  => 'nullable|array',
        ];
    }
}
