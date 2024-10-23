<?php

namespace App\Observers;

use App\Models\EquipmentCategory;
use App\Service\Cache;
use Illuminate\Support\Facades\Log;

class EquipmentCategoryObserver
{
    /** @var User|string */
    protected $user;

    public function __construct()
    {
        $this->user = match (true) {
            ! is_null(request()->user()) => request()->user()->username,
            request()->ip() === '127.0.0.1' => 'Internal Service',
            // @codeCoverageIgnoreStart
            default => request()->ip(),
            // @codeCoverageIgnoreEnd
        };
    }

    public function created(EquipmentCategory $category): void
    {
        Cache::clearCache(['equipmentTypes', 'equipmentCategories']);

        Log::info(
            'New Equipment Category '.$category->name.' created by '.$this->user,
            $category->toArray()
        );
    }

    public function updated(EquipmentCategory $category): void
    {
        Cache::clearCache(['equipmentTypes', 'equipmentCategories']);

        Log::info(
            'Equipment Category '.$category->name.' has been updated by '.
                $this->user
        );
    }

    public function deleted(EquipmentCategory $category): void
    {
        Cache::clearCache(['equipmentTypes', 'equipmentCategories']);

        Log::notice('Equipment Category '.$category->name.' has been deleted by '.
            $this->user
        );
    }
}
