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
            'receive_sms' => 'required|boolean',
            'phone' => 'required_if:sms_notifications,true',
            'settingList' => 'required|array',
        ];
    }

    /**
     * Cycle through each of the settings and update their values
     */
    public function updateSettings()
    {
        foreach ($this->settingList as $key => $value) {
            UserSetting::where('user_id', $this->user->user_id)
                ->where('setting_type_id', str_replace('type_id_', '', $key))
                ->update([
                    'value' => $value,
                ]);
        }
    }

    /**
     * Update the users SMS Notification settings, return boolean if we need to verify the number
     */
    public function doWeReVerify()
    {
        $oldPhone = $this->user()->phone;

        if ($oldPhone !== $this->phone && $this->receive_sms) {
            return true;
        }

        return false;
    }
}
