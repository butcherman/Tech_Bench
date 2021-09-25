<?php

namespace App\Http\Requests\Home;

use Illuminate\Foundation\Http\FormRequest;

class UploadedFileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request
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
