<?php

namespace App\Http\Requests\Equipment;

use App\Models\EquipmentType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EquipmentCategoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        if ($this->equipment_category) {
            return $this->user()->can('update', EquipmentType::class);
        }

        return $this->user()->can('create', EquipmentType::class);
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'name' => [
                'required',
                Rule::unique('equipment_categories')->ignore($this->category),
            ],
        ];
    }
}
