<?php

namespace App\Http\Requests\TechTips;

use App\Models\TechTip;
use Illuminate\Foundation\Http\FormRequest;

class UpdateTipRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request
     */
    public function authorize()
    {
        return $this->user()->can('update', TechTip::find($this->route('tech_tip')));
    }

    /**
     * Get the validation rules that apply to the request
     */
    public function rules()
    {
        return [
            'subject'      => 'required|string',
            'tip_type_id'  => 'required|exists:tech_tip_types',
            'details'      => 'required',
            'resendNotif'  => 'required|boolean',
            'sticky'       => 'required|boolean',
            'equipment'    => 'required|array',
            'fileList'     => 'nullable|array',
            'removedFiles' => 'nullable|array',
        ];
    }
}
