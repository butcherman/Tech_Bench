<?php

namespace App\Traits;

trait CustomerBroadcastingTrait
{
    protected function getCustomerChannel(): array
    {
        return ['customer.'.$this->Customer->slug];
    }

    protected function getSiteChannelList(): array
    {
        $siteList = $this->CustomerSite->pluck('site_slug')->toArray();
        array_walk($siteList, function (&$value) {
            $value = 'customer-site.'.$value;
        });

        return $siteList;
    }

    protected function getEquipmentChannel(): array
    {
        return ['customer-equipment.'.$this->cust_equip_id];
    }
}
