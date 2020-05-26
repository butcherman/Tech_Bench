<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UserSettingsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'em_tech_tip'     => 'required|boolean',
            'em_file_link'    => 'required|boolean',
            'em_notification' => 'required|boolean',
            'auto_del_link'   => 'required|boolean',
        ];
    }
}
