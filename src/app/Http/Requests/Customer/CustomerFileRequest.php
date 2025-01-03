<?php

namespace App\Http\Requests\Customer;

use App\Http\Requests\UploadFileBaseRequest;
use App\Models\CustomerFile;

class CustomerFileRequest extends UploadFileBaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        if ($this->isMethod('PUT')) {
            return $this->user()->can('update', $this->file);
        }

        return $this->user()->can('create', CustomerFile::class);
    }

    /**
     * Get the validation rules that apply to the request
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string'],
            'file_type' => ['required', 'string'],
            'site_list' => ['required_if:file_type,site'],
            'cust_equip_id' => ['required_if:file_type,equipment'],
            'file_type_id' => ['required', 'exists:customer_file_types'],
        ];
    }
}
