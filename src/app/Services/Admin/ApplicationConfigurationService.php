<?php

namespace App\Services\Admin;

use App\Facades\CacheData;
use App\Traits\AppSettingsTrait;
use ArthurPatriot\Tus\Helpers\TusFile;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class ApplicationConfigurationService
{
    use AppSettingsTrait;

    /**
     * Get an array of basic settings from config/db
     */
    public function getConfigSettings(): array
    {
        return [
            'url' => preg_replace('(^https?://)', '', config('app.url')),
            'company_name' => config('app.company_name'),
            'timezone' => config('app.timezone'),
            'max_filesize' => config('filesystems.max_filesize'),
            'welcome_message' => config('app.welcome_message'),
            'home_links' => $this->formatHomeLinks(),
        ];
    }

    /**
     * Update the Basic Settings for Tech Bench
     */
    public function updateBasicSettings(Collection $requestData): void
    {
        $baseUrl = str_replace('https://', '', config('app.url'));

        // Changing the URL will trigger a rebuild of all JS files
        if ($baseUrl !== $requestData->get('url')) {
            // TODO - Create URL Changed Event and Listener
            // event(new UrlChangedEvent($requestData->get('url'), $baseUrl));
            $this->saveSettings('app.url', $requestData->get('url'));
        }

        $setArr = [
            'app.timezone' => $requestData->get('timezone'),
            'app.company_name' => $requestData->get('company_name'),
            'app.schedule_timezone' => $requestData->get('timezone'),
            'filesystems.max_filesize' => $requestData->get('max_filesize'),
            'services.azure.redirect' => 'https://'.$requestData->get('url').'/auth/callback',
        ];

        $this->saveSettingsArray($setArr);

        $this->updateWelcomeMessage($requestData->get('welcome_message'));
        $this->updateHomeLinks($requestData->get('home_links'));
    }

    /*
    |---------------------------------------------------------------------------
    | Home Links & Welcome Message
    |---------------------------------------------------------------------------
    */
    /**
     * Modify the Welcome Message for the home page.
     */
    private function updateWelcomeMessage(?string $welcomeMsg): void
    {
        // Update or delete the welcome message
        if (config('app.welcome_message') !== $welcomeMsg) {
            if ($welcomeMsg) {
                $this->saveSettings('app.welcome_message', $welcomeMsg);
            } else {
                $this->clearSetting('app.welcome_message');
            }
        }
    }

    /**
     * Format the home links for the settings form.
     */
    private function formatHomeLinks(): array
    {
        return array_replace_recursive(
            array_fill(0, 3, [
                'url' => null,
                'text' => null,
            ]),
            config('app.home_links'),
        );
    }

    /**
     * Update the Home Links
     */
    private function updateHomeLinks(array $homeLinks): void
    {
        $newLinks = [];

        foreach ($homeLinks as $link) {
            if (Arr::hasAll($link, ['url', 'text'])) {
                $newLinks[] = [
                    'url' => $link['url'],
                    'text' => $link['text'],
                ];
            }
        }

        $this->saveSettings('app.home_links', $newLinks);
    }

    /*
    |---------------------------------------------------------------------------
    | Application Logo
    |---------------------------------------------------------------------------
    */
    /**
     * Save the Logo File
     */
    public function updateLogo(TusFile $logoFile): string
    {
        // Set the new filename
        $fileParts = pathinfo($logoFile->path);
        $extension = $fileParts['extension'];

        $logoName = 'logo-'.Str::random(10).'.'.$extension;
        $destination = 'images/logo/'.$logoName;

        $this->saveSettings(
            'app.logo',
            '/storage/'.$destination
        );

        return $destination;
    }

    /**
     * Delete the Logo File
     */
    public function destroyLogo(): void
    {
        $this->clearSetting('app.logo');

        CacheData::clearCache('appData');
    }
}
