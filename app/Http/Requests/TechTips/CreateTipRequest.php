<?php

namespace App\Http\Requests\TechTips;

use App\Models\TechTip;
use Illuminate\Foundation\Http\FormRequest;

class CreateTipRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('create', TechTip::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'subject'     => 'required|string',
            'tip_type_id' => 'required|exists:tech_tip_types',
            'details'     => 'required',
            'noEmail'     => 'required|boolean',
            'sticky'      => 'required|boolean',
            'equipment'   => 'required|array',
        ];
    }
}
