<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\AppSettings;

class BackupSettingsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request
     */
    public function authorize()
    {
        return $this->user()->can('viewAny', AppSettings::class);
    }

    /**
     * Get the validation rules that apply to the request
     */
    public function rules()
    {
        return [
            'enable'   => 'required|boolean',
            'password' => 'nullable|string',
            'email'    => 'nullable|string',
        ];
    }

    /**
     * Get the config key base on the field name
     */
    public function getConfigKey($field)
    {
        $key = '';

        switch($field)
        {
            case 'enable':
                $key = 'app.backups.enabled';
                break;
            case 'password':
                $key = 'backup.backup.password';
                break;
            case 'email':
                $key = 'backup.notifications.mail.to';
                break;
        }

        return $key;
    }
}
