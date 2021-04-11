<?php

namespace App\Http\Requests\Equipment;

use App\Models\EquipmentCategory;

use Illuminate\Foundation\Http\FormRequest;

class EquipmentCategoryRequest extends FormRequest
{
    /**
     * Only user with the "Manage Equipment" policy are allowed to submit this request
     */
    public function authorize()
    {
        return $this->user()->can('create', EquipmentCategory::class);
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules()
    {
        return [
            'name' => [
                'required',
                'string',
                'regex:/^[a-zA-Z0-9_ ]*$/',     //  No special characters are allowed
                'unique:equipment_categories',  //  Category name must not already exist
            ]
        ];
    }
}
