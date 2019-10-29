<?php

namespace App\Http\Resources;

use App\Customers;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class FileLinks extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        // return parent::toArray($request);
        return [
            'link_id'      => $this->link_id,
            'user_id'      => $this->user_id,
            'cust_id'      => $this->cust_id,
            'cust_name'    => $this->cust_id ? Customers::find($this->cust_id)->name : 'None',
            'link_hash'    => $this->link_hash,
            'link_name'    => $this->link_name,
            'exp_format'   => Carbon::parse($this->expire)->format('M d, Y'),
            'expired'      => $this->expire < Carbon::now() ? 1 : 0,
            'exp_stamp'    => Carbon::parse($this->expire)->format('Y-m-d'),
            'allow_upload' => $this->allow_upload,
            'file_count'   => isset($this->file_link_files_count) ? $this->file_link_files_count : 0
        ];
    }
}
