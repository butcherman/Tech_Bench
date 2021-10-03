<?php

namespace App\Http\Requests\Customers;

use App\Models\CustomerFileType;
use Illuminate\Foundation\Http\FormRequest;

class CustomerFileTypeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if($this->route('file_type'))
        {
            return $this->user()->can('update', CustomerFileType::find($this->route('file_type')));
        }

        return $this->user()->can('create', CustomerFileType::class);
    }

    /**
     * Get the validation rules that apply to the request
     */
    public function rules()
    {
        return [
            'description' => 'required',
        ];
    }
}
