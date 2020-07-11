<?php

namespace App\Http\Requests\TechTips;

use Illuminate\Foundation\Http\FormRequest;

class EditTipRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('hasAccess', 'Edit Tech Tip');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'subject'         => 'required',
            'equipment'       => 'required',
            'tip_type_id'     => 'required',
            'description'     => 'required',
            'notify'          => 'required',
            'sticky'          => 'required',
            'cur_files'       => 'nullable',
            'deletedFileList' => 'nullable',
        ];
    }
}
