<?php

// TODO - Refactor

namespace App\Http\Requests\Customer;

use App\Models\Customer;
use App\Models\CustomerSite;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class CustomerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        if ($this->customer) {
            return $this->user()->can('update', $this->customer);
        }

        return $this->user()->can('create', Customer::class);
    }

    /**
     * Get the validation rules that apply to the request
     */
    public function rules(): array
    {
        if ($this->customer) {
            return [
                'name' => 'required|string',
                'dba_name' => 'nullable',
                'primary_site_id' => 'required|exists:customer_sites,cust_site_id',
            ];
        }

        return [
            'cust_id' => 'nullable|numeric',
            'name' => 'required|string',
            'dba_name' => 'nullable|string',
            'address' => 'required|string',
            'city' => 'required|string',
            'state' => 'required|string',
            'zip' => 'required|numeric',
        ];
    }

    /**
     * Create a unique slug for the customer link
     */
    public function setSlug()
    {
        $index = 1;
        $slug = Str::slug($this->name);

        while (Customer::where('slug', $slug)->first()) {
            $slug = Str::slug($this->name.'-'.$index);
            $index++;
        }

        $this->merge(['slug' => $slug]);
    }

    /**
     * Create a new customer in the database
     */
    public function createNewCustomer()
    {
        $this->setSlug();

        $newCustomer = Customer::create($this->only([
            'cust_id',
            'name',
            'dba_name',
            'slug',
        ]));

        $newSite = CustomerSite::create([
            'cust_id' => $newCustomer->cust_id,
            'site_name' => $newCustomer->name,
            'site_slug' => $newCustomer->slug,
            'address' => $this->address,
            'city' => $this->city,
            'state' => $this->state,
            'zip' => $this->zip,
        ]);

        $newCustomer->update(['primary_site_id' => $newSite->cust_site_id]);

        return $newCustomer;
    }

    /**
     * Update the basic details of an existing customer
     */
    public function updateCustomer(Customer $customer)
    {
        // Only update the slug if the name has changed
        if ($this->name !== $customer->name) {
            $this->setSlug();
            $customer->slug = $this->slug;
            $customer->save();
        }

        $customer->update($this->only([
            'name',
            'dba_name',
            'primary_site_id',
        ]));

        return $customer;
    }
}
