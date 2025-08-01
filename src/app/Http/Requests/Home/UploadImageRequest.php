<?php

namespace App\Http\Requests\Home;

use Illuminate\Foundation\Http\FormRequest;

class UploadImageRequest extends FormRequest
{
    protected $errorBag = 'form_error';

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user() ? true : false;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'file' => ['mimes:jpeg,bmp,png,jpb,gif,webp'],
        ];
    }
}
