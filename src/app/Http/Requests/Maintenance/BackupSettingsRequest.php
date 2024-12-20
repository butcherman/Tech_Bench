<?php

// TODO - Refactor

namespace App\Http\Requests\Maintenance;

use App\Models\AppSettings;
use App\Traits\AppSettingsTrait;
use Illuminate\Foundation\Http\FormRequest;

class BackupSettingsRequest extends FormRequest
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
            'nightly_backup' => 'required|boolean',
            'nightly_cleanup' => 'required|boolean',
            'encryption' => 'required|boolean',
            'password' => 'required_if:encryption,true',
            'mail_to' => 'required|email',
        ];
    }

    /**
     * Process the different backup config settings
     */
    public function processBackupSettings(): void
    {
        $this->saveSettingsArray($this->only(['nightly_backup', 'nightly_cleanup']), 'backup');
        $this->saveSettings('backup.backup.password', $this->password);
        $this->saveSettings('backup.backup.encryption', $this->encryption ? 'default' : false);
        $this->saveSettings('backup.notifications.mail.to', $this->mail_to);
    }
}
