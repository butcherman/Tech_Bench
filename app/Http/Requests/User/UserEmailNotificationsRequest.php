<?php

namespace App\Http\Requests\User;

use App\Models\UserEmailNotifications;
use Illuminate\Foundation\Http\FormRequest;

class UserEmailNotificationsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {

        $user = UserEmailNotifications::find($this->route('email_notification'));
        return $user && $this->user()->can('update', $user);
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
            'em_notification' => 'required|boolean',
            'em_file_link'    => 'required|boolean',
            'auto_del_link'   => 'required|boolean',
        ];
    }
}
