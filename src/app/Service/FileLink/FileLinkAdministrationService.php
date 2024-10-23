<?php

namespace App\Service\FileLink;

use App\Events\Feature\FeatureChangedEvent;
use App\Traits\AppSettingsTrait;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class FileLinkAdministrationService
{
    use AppSettingsTrait;

    public function saveFileLinkSettings(Collection $requestData)
    {
        $this->saveSettingsArray($requestData->all(), 'file-link');

        event(new FeatureChangedEvent);

        Log::info(
            'File Link Settings updated by '.request()->user()->username,
            $requestData->toArray()
        );
    }
}
