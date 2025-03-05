<?php

namespace App\Http\Requests\Customer;

use App\Models\CustomerSite;
use Illuminate\Foundation\Http\FormRequest;

class CustomerSiteRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        if ($this->site) {
            return $this->user()->can('update', $this->site);
        }

        return $this->user()->can('create', CustomerSite::class);
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'cust_name' => ['required', 'string'],
            'cust_id' => ['required', 'exists:customers'],
            'site_name' => ['required', 'string'],
            'address' => ['required', 'string'],
            'city' => ['required', 'string'],
            'state' => ['required', 'string'],
            'zip' => ['required', 'numeric'],
        ];
    }
}
