<?php

namespace App\Http\Requests\TechTips;

use App\Models\TechTip;
use Illuminate\Foundation\Http\FormRequest;

class TipTypeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request
     */
    public function authorize()
    {
        return $this->user()->can('manage', TechTip::class);
    }

    /**
     * Get the validation rules that apply to the request
     */
    public function rules()
    {
        return [
            'description' => 'required',
            'tip_type_id' => 'nullable|exists:tech_tip_types'
        ];
    }
}
