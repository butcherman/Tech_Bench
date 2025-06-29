<?php

namespace App\Http\Requests\Customer;

use App\Models\CustomerFile;
use App\Traits\Requests\NormalizeJson;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CustomerFileRequest extends FormRequest
{
    use NormalizeJson;

    protected $errorBag = 'form_error';

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        if ($this->routeIs('links.files.update')) {
            return $this->user()->can('update', $this->link);
        }

        if ($this->isMethod('PUT')) {
            return $this->user()->can('update', $this->file);
        }

        return $this->user()->can('create', CustomerFile::class);
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'cust_id' => Rule::requiredIf(fn () => $this->routeIs('links.files.update')),
            'name' => ['required', 'string'],
            'file_type' => ['required', 'string'],
            'site_list' => ['required_if:file_type,site'],
            'cust_equip_id' => ['required_if:file_type,equipment'],
            'file_type_id' => ['required', 'exists:customer_file_types'],
        ];
    }
}
