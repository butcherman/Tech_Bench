<?php

namespace App\Http\Requests\Customers;

use App\Models\CustomerFile;
use Illuminate\Foundation\Http\FormRequest;

class CustomerFileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if($this->route('file'))
        {
            return $this->user()->can('update', CustomerFile::find($this->route('file')));
        }

        return $this->user()->can('create', CustomerFile::class);
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
            'name'    => 'required|string',
            'type'    => 'required|string',
            'shared'  => 'required',
        ];
    }
}
