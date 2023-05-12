<?php

namespace App\Http\Requests\Customers;

use App\Models\Customer;
use App\Models\CustomerNote;
use Illuminate\Foundation\Http\FormRequest;

class CustomerNoteRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request
     */
    public function authorize(): bool
    {
        if ($this->note) {
            return $this->user()->can('update', $this->note);
        }

        return $this->user()->can('create', CustomerNote::class);
    }

    /**
     * Get the validation rules that apply to the request
     */
    public function rules(): array
    {
        return [
            'cust_id' => 'required|exists:customers',
            'subject' => 'required|string',
            'details' => 'required|string',
            'shared' => 'required|boolean',
            'urgent' => 'required|boolean',
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
}
