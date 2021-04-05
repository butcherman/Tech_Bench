<?php

namespace App\Http\Requests\Equipment;

use App\Models\EquipmentCategory;
use Illuminate\Foundation\Http\FormRequest;

class EquipmentCategoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('create', EquipmentCategory::class);
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
                'unique:equipment_categories',
            ]
        ];
    }
}
