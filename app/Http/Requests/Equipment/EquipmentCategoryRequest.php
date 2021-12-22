<?php

namespace App\Http\Requests\Equipment;

use App\Models\EquipmentCategory;
use App\Models\EquipmentType;
use Illuminate\Foundation\Http\FormRequest;

class EquipmentCategoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request
     */
    public function authorize()
    {
        if($this->route('equipment_category'))
        {
            return $this->user()->can('update', EquipmentCategory::find($this->route('equipment_category')));
        }

        return $this->user()->can('create', EquipmentCategory::class);
    }

    /**
     * Get the validation rules that apply to the request
     */
    public function rules()
    {
        return [
            'name' => 'required|unique:equipment_categories,name,'.$this->route('equipment_category').',cat_id',
        ];
    }
}
