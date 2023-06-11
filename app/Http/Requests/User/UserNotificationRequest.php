<?php

namespace App\Http\Requests\User;

use App\Models\UserSetting;
use Illuminate\Foundation\Http\FormRequest;

class UserNotificationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->user);
    }

    /**
     * Get the validation rules that apply to the request
     */
    public function rules(): array
    {
        return [
            'settingsData' => 'required|array',
        ];
    }

    /**
     * Cycle through each of the settings and update their values
     */
    public function updateSettings()
    {
        foreach($this->settingsData as $key => $value) {
            UserSetting::where('user_id', $this->user->user_id)
                ->where('setting_type_id', str_replace('type_id_', '', $key))
                ->update([
                'value' => $value,
            ]);
        }
    }
}
