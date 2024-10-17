<?php

namespace App\Traits;

use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Support\Facades\Log;

trait CustomerBroadcastingTrait
{
    // protected function getSiteChannelList(): array
    // {
    //     $siteList = $this->CustomerSite->pluck('site_slug')->toArray();
    //     array_walk($siteList, function (&$value) {
    //         $value = 'customer-site.'.$value;
    //     });

    //     return $siteList;
    // }

    // protected function getEquipmentChannel(): array
    // {
    //     return ['customer-equipment.'.$this->cust_equip_id];
    // }

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
