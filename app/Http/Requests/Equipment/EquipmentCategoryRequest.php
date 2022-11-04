<?php

namespace App\Http\Requests\Equipment;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use App\Models\EquipmentCategory;

class EquipmentCategoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request
     */
    public function authorize()
    {
        if($this->equipment_category)
        {
            return $this->user()->can('update', $this->equipment_category);
        }

        return $this->user()->can('create', EquipmentCategory::class);
    }

    /**
     * Get the validation rules that apply to the request
     */
    public function rules()
    {
        return [
            'name' => [
                'required',
                Rule::unique('equipment_categories')->ignore($this->equipment_category, 'cat_id')
            ]
        ];
    }
}
