<?php

namespace App\Http\Requests\Admin;

use App\Models\AppSettings;
use Illuminate\Foundation\Http\FormRequest;

class EmailSettingsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('viewAny', AppSettings::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'from_address'   => 'required|email',
            'username'       => 'nullable',
            'password'       => 'nullable',
            'host'           => 'required',
            'port'           => 'required|numeric',
            'encryption'     => 'required',
            'authentication' => 'required|boolean',
        ];
    }
}
