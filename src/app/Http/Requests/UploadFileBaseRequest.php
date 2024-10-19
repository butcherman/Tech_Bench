<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class UploadFileBaseRequest extends FormRequest
{
    /**
     * If JSON data was part of the request, normalize it before validation
     */
    public function prepareForValidation()
    {
        foreach ($this->all() as $key => $value) {
            if (Str::isJson($value)) {
                $this->merge([$key => json_decode($value)]);
            }
        }
    }
}
