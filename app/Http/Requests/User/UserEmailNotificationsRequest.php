<?php

namespace App\Http\Requests\User;

use App\Models\UserEmailNotifications;

use Illuminate\Foundation\Http\FormRequest;

class UserEmailNotificationsRequest extends FormRequest
{
    /**
     * Users can update their own notification settings, or an administrator with "Manage Users" permissions can do it for them
     */
    public function authorize()
    {

        $user = UserEmailNotifications::find($this->route('email_notification'));
        return $user && $this->user()->can('update', $user);
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules()
    {
        return [
            'em_tech_tip'     => 'required|boolean',
            'em_notification' => 'required|boolean',
            'em_file_link'    => 'required|boolean',
            'auto_del_link'   => 'required|boolean',
        ];
    }
}
