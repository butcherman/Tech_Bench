<?php

namespace App\Http\Requests\Reports;

use Illuminate\Foundation\Http\FormRequest;

class UserPermissionsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request
     */
    public function rules()
    {
        return [
            'selected_list' => 'required|array'
        ];
    }
}
