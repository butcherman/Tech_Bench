<?php

namespace App\Http\Requests\Admin;

use App\Models\AppSettings;
use App\Traits\AppSettingsTrait;
use Illuminate\Foundation\Http\FormRequest;

class AppConfigRequest extends FormRequest
{
    use AppSettingsTrait;

    /**
     * Determine if the user is authorized to make this request.
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
            'url' => 'required|string',
            'timezone' => 'required|string',
            'max_filesize' => 'required|numeric',
        ];
    }

    /**
     * Since the keys have multiple prefixes, we will create a new array to process the changes
     */
    public function processSettings()
    {
        $this->updateEnvWsHost();

        $setArr = [
            'app.url' => $this->url,
            'app.timezone' => $this->timezone,
            'filesystems.max_filesize' => $this->max_filesize,
            'services.azure.redirect' => $this->url.'/auth/callback',
        ];

        $this->saveSettingsArray($setArr);
    }

    /**
     * Write the new APP_URL to the .env file as VITE_WS_HOST
     * This is used for Soketi Broadcasting Configuration
     */
    protected function updateEnvWsHost()
    {
        $oldHost = preg_replace("(^https?://)", "", config('app.url') );
        $newHost = preg_replace("(^https?://)", "", $this->url );
        $envFile = base_path('.env');

        if (file_exists($envFile)) {
            file_put_contents($envFile, str_replace(
                'VITE_WS_HOST='.$oldHost, 'VITE_WS_HOST='.$newHost, file_get_contents($envFile)
            ));
        }
    }
}
