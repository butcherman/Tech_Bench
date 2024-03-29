<?php

namespace App\Http\Requests\User;

use App\Models\User;

use Illuminate\Foundation\Http\FormRequest;

class UserNotificationsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request
     */
    public function authorize()
    {
        return $this->user()->can('update', User::find($this->user_id));
    }

    /**
     * Get the validation rules that apply to the request
     */
    public function rules()
    {
        return [
            'settingsData' => 'required|array',
            'user_id'      => 'required|exists:users',
        ];
    }
}
