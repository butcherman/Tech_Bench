<?php

namespace App\Traits;

use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Support\Facades\Log;

trait CustomerBroadcastingTrait
{
    protected function getSiteChannels(array $siteSlugs): array
    {
        $channelList = [];

        foreach ($siteSlugs as $slug) {
            $channelList[] = new PrivateChannel('customer-site.'.$slug);
        }

        Log::debug('Customer Site Channel List created', $channelList);

        return $channelList;
    }
}
