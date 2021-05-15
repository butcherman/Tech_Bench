<?php

namespace App\Http\Requests\Customers;

use App\Models\CustomerNote;
use Illuminate\Foundation\Http\FormRequest;

class CustomerNoteRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if($this->route('note'))
        {
            return $this->user()->can('update', CustomerNote::find($this->route('note')));
        }

        return $this->user()->can('create', CustomerNote::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'cust_id' => 'required|exists:customers',
            'subject' => 'required|string',
            'details' => 'required|string',
            'shared'  => 'required|boolean',
            'urgent'  => 'required|boolean',
        ];
    }
}
