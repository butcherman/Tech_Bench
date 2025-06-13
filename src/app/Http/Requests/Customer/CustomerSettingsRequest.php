<?php

namespace App\Http\Requests\Customer;

use App\Models\Customer;
use Illuminate\Foundation\Http\FormRequest;

class CustomerSettingsRequest extends FormRequest
{
    protected $errorBag = 'form_error';

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('manage', Customer::class);
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'select_id' => ['required', 'boolean'],
            'update_slug' => ['required', 'boolean'],
            'default_state' => ['required', 'string'],
            'auto_purge' => ['required', 'boolean'],
            'allow_vpn_data' => ['required', 'boolean'],
        ];
    }
}
