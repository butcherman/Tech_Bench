<?php

namespace App\Http\Requests\Customers;

use App\Models\Customer;
use App\Models\CustomerFile;
use App\Models\CustomerFileType;
use Illuminate\Foundation\Http\FormRequest;

class CustomerFileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        if($this->customerFile)
        {
            return $this->user()->can('update', $this->customerFile);
        }

        return $this->user()->can('create', CustomerFile::class);
    }

    /**
     * Get the validation rules that apply to the request
     */
    public function rules(): array
    {
        return [
            'cust_id' => 'required|exists:customers',
            'name' => 'required|string',
            'type' => 'required|string',
            'shared' => 'required|string',
        ];
    }

    /**
     * Check if the item is shared, if so change the id field to be the parent id
     */
    public function checkForShared()
    {
        if ($this->shared) {
            $cust = Customer::find($this->cust_id);
            if ($cust->parent_id) {
                $this->merge(['cust_id' => $cust->parent_id]);
            }
        }
    }

    /**
     * Append the File ID and File Type ID to the request data
     */
    public function appendFileData($fileId)
    {
        $type = CustomerFileType::where('description', $this->type)->first();
        $this->merge([
            'file_type_id' => $type->file_type_id,
            'file_id' => $fileId,
            'shared' => (bool) $this->shared,
            'user_id' => $this->user()->user_id,
        ]);
    }
}
