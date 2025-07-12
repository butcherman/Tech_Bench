<?php

namespace App\Http\Requests\Equipment;

use App\Models\EquipmentType;
use Illuminate\Foundation\Http\FormRequest;

class EquipmentWorkbookRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('viewAny', EquipmentType::class);
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'workbook_data' => 'required|array',
        ];
    }
}
