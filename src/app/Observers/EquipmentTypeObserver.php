<?php

namespace App\Observers;

use App\Models\EquipmentType;
use App\Service\Cache;
use Illuminate\Support\Facades\Log;

class EquipmentTypeObserver
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

    /**
     * Handle the EquipmentType "created" event.
     */
    public function created(EquipmentType $equipmentType): void
    {
        Cache::clearCache(['equipmentTypes', 'equipmentCategories']);

        Log::info(
            'New Equipment Type created by '.$this->user,
            $equipmentType->toArray()
        );
    }

    /**
     * Handle the EquipmentType "updated" event.
     */
    public function updated(EquipmentType $equipmentType): void
    {
        Cache::clearCache(['equipmentTypes', 'equipmentCategories']);

        Log::info(
            'Equipment Type '.$equipmentType->name.' updated by '.$this->user,
            $equipmentType->toArray()
        );
    }

    /**
     * Handle the EquipmentType "deleted" event.
     */
    public function deleted(EquipmentType $equipmentType): void
    {
        Cache::clearCache(['equipmentTypes', 'equipmentCategories']);

        Log::notice(
            'Equipment Type '.$equipmentType->name.' was deleted by '.$this->user
        );
    }
}
