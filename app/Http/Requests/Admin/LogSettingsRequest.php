<?php

namespace App\Http\Requests\Admin;

use App\Models\AppSettings;
use App\Traits\AppSettingsTrait;
use Illuminate\Foundation\Http\FormRequest;

class LogSettingsRequest extends FormRequest
{
    use AppSettingsTrait;

    /**
     * Determine if the user is authorized to make this request
     */
    public function authorize(): bool
    {
        return $this->user()->can('viewAny', AppSettings::class);
    }

    /**
     * Get the validation rules that apply to the request
     */
    public function rules(): array
    {
        return [
            'days' => 'required|numeric',
            // 'levels' => [
            'level.auth' => 'required|string',
            'level.cust' => 'required|string',
            'level.daily' => 'required|string',
            'level.tip' => 'required|string',
            'level.user' => 'required|string',
            // ]
        ];
    }

    /**
     * Process the log settings request
     */
    public function processRequest()
    {
        $settingsData = ['logging.days' => $this->days];
        foreach ($this->level as $level => $value) {
            $settingsData['logging.channels.'.$level.'.days'] = $this->days;
            $settingsData['logging.channels.'.$level.'.level'] = strtolower($value);
        }

        $this->saveSettingsArray($settingsData);
    }
}
