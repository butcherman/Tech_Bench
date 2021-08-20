<?php

namespace App\Http\Requests\Home;

use Illuminate\Foundation\Http\FormRequest;

class UploadFilerequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        //  TODO - Auth Check
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
            'file'   => 'required',
            'disk'   => 'required',
            'folder' => 'required',
            'multi'  => 'required',
            'public' => 'required',
        ];
    }
}
