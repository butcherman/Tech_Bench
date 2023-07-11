<?php

namespace App\Http\Requests\Equipment;

use App\Models\EquipmentType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EquipmentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request
     */
    public function authorize(): bool
    {
        if ($this->equipment) {
            return $this->user()->can('update', $this->equipment);
        } else {
            return $this->user()->can('create', EquipmentType::class);
        }
    }

    /**
     * Get the validation rules that apply to the request
     */
    public function rules(): array
    {
        return [
            'cat_id' => 'required|exists:equipment_categories',
            'name' => ['required', Rule::unique('equipment_types')->ignore($this->equipment)],
            'custData' => 'required|array',
        ];
    }
}
