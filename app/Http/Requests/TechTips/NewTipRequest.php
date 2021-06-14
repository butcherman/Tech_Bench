<?php

namespace App\Http\Requests\TechTips;

use App\Models\TechTip;
use Illuminate\Foundation\Http\FormRequest;

class NewTipRequest extends FormRequest
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
            'subject'     => 'required',
            'tip_type_id' => 'required|exists:tech_tip_types',
            'details'     => 'required',
            'noEmail'     => 'required',
            'sticky'      => 'required',
            'equipment'   => 'required',
        ];
    }
}
