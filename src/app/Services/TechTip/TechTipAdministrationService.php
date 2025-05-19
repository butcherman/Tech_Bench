<?php

namespace App\Services\TechTip;

use App\Traits\AppSettingsTrait;
use Illuminate\Support\Collection;

class TechTipAdministrationService
{
    use AppSettingsTrait;

    /**
     * Get the Tech Tip Settings
     */
    public function getTechTipSettings(): Collection
    {
        return collect([
            'allow_comments' => config('tech-tips.allow_comments'),
            'allow_download' => config('tech-tips.allow_download'),
            'allow_public' => config('tech-tips.allow_public'),
            'public_link_text' => config('tech-tips.public_link_text'),
        ]);
    }

    /**
     * Update the Tech Tip Settings.
     */
    public function updateTechTipSettings(Collection $requestData): void
    {
        $this->saveSettingsArray($requestData->toArray(), 'tech-tips');
    }
}
