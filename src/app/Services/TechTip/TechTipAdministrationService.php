<?php

namespace App\Services\TechTip;

use App\Facades\DbException;
use App\Models\TechTipType;
use App\Traits\AppSettingsTrait;
use Illuminate\Database\QueryException;
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

    /**
     * Create a new Tech Tip Type
     */
    public function createTechTipType(Collection $requestData): TechTipType
    {
        $newType = TechTipType::create($requestData->toArray());

        return $newType;
    }

    /**
     * Update the description of a Tech Tip Type.
     */
    public function updateTechTipType(Collection $requestData, TechTipType $tipType): TechTipType
    {
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
            DbException::check(
                $e,
                'Unable to delete.  This Tech Tip Type has at least one Tech Tip assigned to it.'
            );
        }
    }
}
