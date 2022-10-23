<?php

namespace App\Http\Requests\Admin;

use App\Models\AppSettings;
use Illuminate\Foundation\Http\FormRequest;

class EmailSettingsRequest extends FormRequest
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
            'from_address'   => 'required|email',
            'username'       => 'nullable|string',
            'password'       => 'nullable|string',
            'host'           => 'required|string',
            'port'           => 'required|numeric',
            'encryption'     => 'required|string',
            'requireAuth'    => 'required|boolean',
        ];
    }

    /**
     * Get the config key based on field name
     */
    public function getConfigKey($field)
    {
        $key = '';

        switch($field)
        {
            case 'from_address':
                $key = 'mail.from.address';
                break;
            case 'host':
                $key = 'mail.mailers.smtp.host';
                break;
            case 'port':
                $key = 'mail.mailers.smtp.port';
                break;
            case 'encryption':
                $key = 'mail.mailers.smtp.encryption';
                break;
            case 'username':
                $key = 'mail.mailers.smtp.username';
                break;
            case 'password':
                $key = 'mail.mailers.smtp.password';
                break;
            case 'requireAuth':
                $key = 'mail.mailers.smtp.require_auth';
                break;
        }

        return $key;
    }
}
