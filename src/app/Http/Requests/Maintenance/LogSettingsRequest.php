<?php

namespace App\Http\Requests\Maintenance;

use App\Models\AppSettings;
use App\Traits\AppSettingsTrait;
use Illuminate\Foundation\Http\FormRequest;

class LogSettingsRequest extends FormRequest
{
    use AppSettingsTrait;

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('update', AppSettings::class);
    }

    /**
     * Get the validation rules that apply to the request
     */
    public function rules(): array
    {
        return [
            'days' => 'required|numeric',
            'log_level' => 'required|string',
        ];
    }

    public function saveLogSettings($logChannels)
    {
        $this->saveSettings('logging.days', $this->days);
        $this->saveSettings('logging.log_level', $this->log_level);

        foreach ($logChannels as $channel) {
            $this->saveSettings('logging.channels.' . $channel['channel'] . '.level', $this->log_level);
            $this->saveSettings('logging.channels.' . $channel['channel'] . '.days', $this->days);
        }
    }
}
