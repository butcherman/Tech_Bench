<?php

namespace App\Http\Requests\TechTips;

use Illuminate\Foundation\Http\FormRequest;

class TechTipBookmarkRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'tip_id' => 'required|exists:tech_tips',
            'state'  => 'required|boolean',
        ];
    }
}
