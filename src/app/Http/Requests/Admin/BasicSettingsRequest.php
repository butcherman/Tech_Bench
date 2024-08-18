<?php

namespace App\Http\Requests\Admin;

use App\Models\AppSettings;
use App\Traits\AppSettingsTrait;
use Illuminate\Foundation\Http\FormRequest;

class BasicSettingsRequest extends FormRequest
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
            'url' => 'required|string',
            'company_name' => 'required|string',
            'timezone' => 'required|string',
            'max_filesize' => 'required|numeric',
        ];
    }

    /**
     * Since the keys have multiple prefixes, we will create a new array to process the changes
     */
    public function processSettings()
    {
        if (config('app.url') !== $this->url) {
            // TODO - Add this back in
            // event(new AppUrlChangedEvent($this->url, config('app.url')));
            $this->saveSettings('app.url', $this->url);
        }

        $setArr = [
            'app.timezone' => $this->timezone,
            'app.company_name' => $this->company_name,
            'app.schedule_timezone' => $this->timezone,
            'filesystems.max_filesize' => $this->max_filesize,
            'services.azure.redirect' => $this->url.'/auth/callback',
        ];

        $this->saveSettingsArray($setArr);
    }
}
