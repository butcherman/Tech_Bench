<?php

namespace App\Http\Requests\Customers;

use Illuminate\Support\Str;
use Illuminate\Foundation\Http\FormRequest;
use App\Models\Customer;

class CustomerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request
     */
    public function authorize()
    {
        if($this->customer)
        {
            return $this->user()->can('update', $this->customer);
        }

        return $this->user()->can('create', Customer::class);
    }

    /**
     * Get the validation rules that apply to the request
     */
    public function rules()
    {
        return [
            'cust_id'     => 'nullable|numeric|unique:customers',
            'parent_id'   => 'nullable|numeric|exists:customers',
            'parent_name' => 'nullable|string',
            'name'        => 'required|string',
            'dba_name'    => 'nullable|string',
            'address'     => 'required|string',
            'city'        => 'required|string',
            'state'       => 'required|string',
            'zip'         => 'required|numeric',
        ];
    }

    /**
     * Set the SLUG value for the customer
     */
    public function setSlug()
    {
        $index = 0;
        $slug  = Str::slug($this->name);

        while(Customer::where('slug', $slug)->first())
        {
            if($index === 0)
            {
                $slug = Str::slug($slug.'-'.$this->city);
            }
            else
            {
                $slug = Str::slug($this->name.'-'.$index);
            }

            $index++;
        }

        $this->merge(['slug' => $slug]);
    }
}
