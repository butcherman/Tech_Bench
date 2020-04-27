<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TechTipEditTipRequest extends FormRequest
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
            'tip_id'          => 'required',
            'subject'         => 'required',
            'system_types'    => 'required',
            'tip_type_id'     => 'required',
            'description'     => 'required',
            'deletedFileList' => 'nullable',
        ];
    }
}
