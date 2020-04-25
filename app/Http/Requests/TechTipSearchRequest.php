<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TechTipSearchRequest extends FormRequest
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
            'search.searchText'  => 'nullable',
            'search.articleType' => 'nullable',
            'search.systemType'  => 'nullable',
            'pagination.rows'    => 'nullable',
            'pagination.low'     => 'nullable',
            'pagination.high'    => 'nullable',
            'pagination.perPage' => 'required',
            'page'               => 'required',
        ];
    }
}
