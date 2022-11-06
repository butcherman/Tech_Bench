<?php

namespace App\Http\Requests\Equipment;

use App\Models\EquipmentType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DataTypeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request
     */
    public function authorize()
    {
        return $this->user()->can('viewAny', EquipmentType::class);
    }

    /**
     * Get the validation rules that apply to the request
     */
    public function rules()
    {
        return [
            'name' => [
                'required',
                'string',
                Rule::unique('data_field_types')->ignore($this->data_type, 'type_id'),
            ],
        ];
    }
}
