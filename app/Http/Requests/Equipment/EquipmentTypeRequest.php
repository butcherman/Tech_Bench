<?php

namespace App\Http\Requests\Equipment;

use App\Models\EquipmentType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EquipmentTypeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request
     */
    public function authorize()
    {
        if ($this->equipment) {
            return $this->user()->can('update', $this->equipment);
        }

        return $this->user()->can('create', EquipmentType::class);
    }

    /**
     * Get the validation rules that apply to the request
     */
    public function rules()
    {
        return [
            'category' => 'required|exists:equipment_categories,name',
            'name' => [
                'required',
                Rule::unique('equipment_types')->ignore($this->equipment, 'equip_id'),
            ],
            'custData' => 'required|array',
        ];
    }
}
