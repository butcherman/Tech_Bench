<?php

namespace App\Services\FileLink;

use App\Events\Feature\FeatureChangedEvent;
use App\Traits\AppSettingsTrait;
use Illuminate\Support\Collection;

class FileLinkAdministrationService
{
    use AppSettingsTrait;

    /**
     * Get the File Link Settings
     */
    public function getFileLinkSettings(): array
    {
        return [
            'feature_enabled' => (bool) config('file-link.feature_enabled'),
            'default_link_life' => config('file-link.default_link_life'),
            'auto_delete' => (bool) config('file-link.auto_delete'),
            'auto_delete_days' => config('file-link.auto_delete_days'),
            'auto_delete_override' => (bool) config('file-link.auto_delete_override'),
        ];
    }

    /**
     * Update the File Link Settings in the DB
     */
    public function saveFileLinkSettings(Collection $requestData)
    {
        $this->saveSettingsArray($requestData->all(), 'file-link');

        event(new FeatureChangedEvent);
    }
}
