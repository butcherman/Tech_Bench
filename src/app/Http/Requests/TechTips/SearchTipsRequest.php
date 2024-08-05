<?php

namespace App\Http\Requests\TechTips;

use Illuminate\Foundation\Http\FormRequest;

class SearchTipsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        if ($this->routeIs('publicTips.search')) {
            return true;
        }

        return $this->user() ? true : false;
    }

    /**
     * Get the validation rules that apply to the request
     */
    public function rules(): array
    {
        return [
            'searchFor' => 'nullable|string',
            'typeList' => 'array',
            'equipList' => 'array',
            'page' => 'required|numeric',
            'perPage' => 'required|numeric',
        ];
    }
}
