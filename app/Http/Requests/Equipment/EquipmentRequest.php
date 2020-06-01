<?php

namespace App\Http\Requests\Equipment;

use Illuminate\Foundation\Http\FormRequest;

class EquipmentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('hasAccess', 'Manage Equipment');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => [
                'required',
                'string',
                'regex:/^[a-zA-Z0-9_ ]*$/',
            ],
            'sys_id' => 'nullable|exists:system_types',
            'system_data_fields' => 'required|array',
            'cat_id' => 'nullable|exists:system_categories',
        ];
    }
}
