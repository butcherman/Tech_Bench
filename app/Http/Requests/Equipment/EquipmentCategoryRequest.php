<?php

namespace App\Http\Requests\Equipment;

use App\Models\EquipmentCategory;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EquipmentCategoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if($this->equipmentCategory)
        {
            return $this->user()->can('update', EquipmentCategory::find($this->equipmentCategory));
        }

        return $this->user()->can('create', EquipmentCategory::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => [
                'required',
                Rule::unique('equipment_categories')->ignore($this->cat_id, 'cat_id')
            ]
        ];
    }
}
