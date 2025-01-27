<?php

namespace App\Services\Maintenance;

use App\Enums\LogLevels;
use App\Traits\AppSettingsTrait;
use Illuminate\Support\Collection;

class LogUtilitiesService
{
    use AppSettingsTrait;

    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Return the possible log levels.
     */
    public function getLogLevels(): array
    {
        return array_column(LogLevels::cases(), 'name');
    }

    /**
     * Save the Log Settings
     */
    public function updateLogSettings(Collection $settings): void
    {
        $this->saveSettings(
            'logging.channels.daily.days',
            $settings->get('days')
        );
        $this->saveSettings(
            'logging.channels.daily.level',
            $settings->get('log_level')
        );

        $this->saveSettings(
            'logging.channels.auth.days',
            $settings->get('days')
        );
        $this->saveSettings(
            'logging.channels.auth.level',
            $settings->get('log_level')
        );
    }
}
