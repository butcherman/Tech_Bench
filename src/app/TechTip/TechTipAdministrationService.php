<?php

namespace App\TechTip;

use App\Events\Feature\FeatureChangedEvent;
use App\Traits\AppSettingsTrait;
use Illuminate\Support\Collection;

class TechTipAdministrationService
{
    use AppSettingsTrait;

    /**
     * Save Tech Tip Settings.
     */
    public function updateTechTipSettings(Collection $requestData)
    {
        $this->saveSettings(
            'tech-tips.allow_public',
            $requestData->get('allow_public')
        );
        $this->saveSettings(
            'tech-tips.allow_comments',
            $requestData->get('allow_comments')
        );

        event(new FeatureChangedEvent);
    }
}
