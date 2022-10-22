<?php

namespace App\Http\Requests\Admin;

use App\Models\AppSettings;
use Illuminate\Foundation\Http\FormRequest;

class SettingsRequest extends FormRequest
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
            'url'           => 'required|string',
            'timezone'      => 'required|string',
            'filesize'      => 'required|numeric',
            'allowOath'     => 'required|boolean',
            'allowRegister' => 'nullable|boolean',
            'tenantId'      => 'nullable|string',
            'clientId'      => 'nullable|string',
            'clientSecret'  => 'nullable|string',
            'redirectUri'   => 'required|string',
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
            case 'url':
                $key = 'app.url';
                break;
            case 'timezone':
                $key = 'app.timezone';
                break;
            case 'filesize':
                $key = 'filesystems.max_filesize';
                break;
            case 'allowOath':
                $key = 'services.azure.allow_login';
                break;
            case 'allowRegister':
                $key = 'services.azure.allow_register';
                break;
            case 'tenantId':
                $key = 'services.azure.tenant';
                break;
            case 'clientId':
                $key = 'services.azure.client_id';
                break;
            case 'clientSecret':
                $key = 'services.azure.client_secret';
                break;
            case 'redirectUri':
                $key = 'services.azure.redirect';
                break;
        }

        return $key;
    }
}
