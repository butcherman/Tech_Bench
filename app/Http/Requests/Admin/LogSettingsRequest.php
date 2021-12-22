<?php

namespace App\Http\Requests\Admin;

use App\Models\AppSettings;
use Illuminate\Foundation\Http\FormRequest;

class LogSettingsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request
     */
    public function authorize()
    {
        return $this->user()->can('viewAny', AppSettings::class); ;
    }

    /**
     * Get the validation rules that apply to the request
     */
    public function rules()
    {
        return [
            'days'  => 'required|numeric',
            'level' => 'required|string',
        ];
    }
}
