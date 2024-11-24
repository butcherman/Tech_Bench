<?php

namespace App\Services\TechTip;

use App\Events\Feature\FeatureChangedEvent;
use App\Facades\DbException;
use App\Models\TechTipType;
use App\Traits\AppSettingsTrait;
use Illuminate\Database\QueryException;
use Illuminate\Support\Collection;

class TechTipAdministrationService
{
    use AppSettingsTrait;

    /**
     * Save Tech Tip Settings.
     */
    public function updateTechTipSettings(Collection $requestData): void
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

    /**
     * Create a new Tech Tip Type
     */
    public function createTechTipType(Collection $requestData): TechTipType
    {
        $newType = TechTipType::create($requestData->toArray());

        return $newType;
    }

    /**
     * Update an existing Tech Tip Type
     */
    public function updateTechTipType(
        Collection $requestData,
        TechTipType $tipType
    ): TechTipType {
        $tipType->update($requestData->toArray());

        return $tipType->fresh();
    }

    /**
     * Remove a Tech Tip Type - Note:  cannot be removed if in use
     */
    public function destroyTechTipType(TechTipType $tipType): void
    {
        try {
            $tipType->delete();
        } catch (QueryException $e) {
            DbException::check($e);
        }
    }
}
