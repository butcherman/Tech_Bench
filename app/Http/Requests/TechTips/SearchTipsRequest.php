<?php

namespace App\Http\Requests\TechTips;

use Illuminate\Foundation\Http\FormRequest;

class SearchTipsRequest extends FormRequest
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
            'search_text'        => 'nullable|string',
            'search_type'        => 'nullable|array',
            'search_equip_id'    => 'nullable|array',
            'pagination_perPage' => 'required|numeric',
            'page'               => 'required|numeric',
        ];
    }
}
