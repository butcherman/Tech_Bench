<?php

namespace App\Http\Requests\Admin;

use App\Events\Config\UrlChangedEvent;
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
        $baseUrl = str_replace('https://', '', config('app.url'));
        if ($baseUrl !== $this->url) {
            event(new UrlChangedEvent($this->url, $baseUrl));
            $this->saveSettings('app.url', $this->url);
        }

        $setArr = [
            'app.timezone' => $this->timezone,
            'app.company_name' => $this->company_name,
            'app.schedule_timezone' => $this->timezone,
            'filesystems.max_filesize' => $this->max_filesize,
            'services.azure.redirect' => 'https://'.$this->url.'/auth/callback',
        ];

        $this->saveSettingsArray($setArr);
    }
}
