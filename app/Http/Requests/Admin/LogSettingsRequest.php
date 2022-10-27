<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\AppSettings;

class LogSettingsRequest extends FormRequest
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
            'days'  => 'required|numeric',
            'level' => 'required|string',
        ];
    }

    public function getConfigkey($field)
    {
        $key = '';

        switch($field)
        {
            case 'days':
                $key = 'logging.days';
                break;
            case 'level':
                $key = 'logging.log_level';
                break;
        }

        return $key;
    }
}
