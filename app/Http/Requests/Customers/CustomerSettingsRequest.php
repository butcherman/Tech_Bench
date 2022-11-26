<?php

namespace App\Http\Requests\Customers;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Customer;

class CustomerSettingsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request
     */
    public function authorize()
    {
        return $this->user()->can('manage', Customer::class);
    }

    /**
     * Get the validation rules that apply to the request
     */
    public function rules()
    {
        return [
            'selectId'     => 'required|boolean',
            'updateSlug'   => 'required|boolean',
            'defaultState' => 'required|string',
        ];
    }

    /**
     * Get the config key based on field name
     */
    public function getConfigKey($field)
    {
        $key = '';

        switch($field)
        {
            case 'selectId':
                $key = 'customer.select_id';
                break;
            case 'updateSlug':
                $key = 'customer.update_slug';
                break;
            case 'defaultState':
                $key = 'customer.default_state';
                break;
        }

        return $key;
    }
}
