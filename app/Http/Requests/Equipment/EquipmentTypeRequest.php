<?php

namespace App\Http\Requests\Equipment;

use App\Models\EquipmentType;
use Illuminate\Foundation\Http\FormRequest;

class EquipmentTypeRequest extends FormRequest
{
    /**
     *  Only users with "Manage Equipment" policy are allowed to submit the request
     */
    public function authorize()
    {
        return $this->user()->can('create', EquipmentType::class);
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules()
    {
        return [
            'cat_id' => 'required|exists:equipment_categories,name',        //  Must contain a valid Category ID
            'name'   => [
                'required',
                'string',
                'regex:/^[a-zA-Z0-9_ ]*$/',                                 //  No special characters are allowed
                'unique:equipment_categories',                              //  Equipment with this name must not already exist
            ]
        ];
    }
}
