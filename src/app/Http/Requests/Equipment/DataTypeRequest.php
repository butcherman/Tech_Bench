<?php

// TODO - Refactor

namespace App\Http\Requests\Equipment;

use App\Models\EquipmentType;
use Illuminate\Foundation\Http\FormRequest;

class DataTypeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('viewAny', EquipmentType::class);
    }

    /**
     * Get the validation rules that apply to the request
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string',
            'pattern' => 'nullable',
            'masked' => 'required|boolean',
            'is_hyperlink' => 'required|boolean',
            'allow_copy' => 'required|boolean',
            'do_not_log_value' => 'required|boolean',
        ];
    }
}
