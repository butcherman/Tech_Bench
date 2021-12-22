<?php

namespace App\Http\Requests\Equipment;

use App\Models\EquipmentType;
use Illuminate\Foundation\Http\FormRequest;

class EquipmentTypeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if($this->route('equipment'))
        {
            return $this->user()->can('update', EquipmentType::find($this->route('equipment')));
        }

        return $this->user()->can('create', EquipmentType::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'category'    => 'required|exists:equipment_categories,name',
            'name'        => 'required|string|unique:equipment_types,name,'.$this->route('equipment').',equip_id',
            'data_fields' => 'required|array',
        ];
    }
}
