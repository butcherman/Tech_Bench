<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\ResourceCollection;

class FileLinksCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $collection = $this->collection;
        foreach($collection as $item)
        {
            if($item['expire'] < Carbon::now())
            {
                $item['expired'] = 1;
            }
            else
            {
                $item['expired'] = 0;
            }
        }

        return parent::toArray(
        /** @scrutinizer ignore-type */
        $collection);
    }
}
