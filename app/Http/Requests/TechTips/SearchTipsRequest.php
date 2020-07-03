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
            'search.searchText'  => 'nullable|string',
            'search.articleType' => 'nullable|array',
            'search.systemType'  => 'nullable|array',
            'pagination.rows'    => 'nullable|numeric',
            'pagination.low'     => 'nullable|numeric',
            'pagination.high'    => 'nullable|numeric',
            'pagination.perPage' => 'required|numeric',
            'page'               => 'required|numeric',
        ];
    }
}
