<?php

namespace App\Http\Requests\Equipment;

use App\Models\EquipmentCategory;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EquipmentCategoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        if ($this->category) {
            return $this->user()->can('update', $this->category);
        }

        return $this->user()->can('create', EquipmentCategory::class);
    }

    /**
     * Get the validation rules that apply to the request
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
