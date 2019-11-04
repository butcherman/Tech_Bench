<?php

namespace App\Http\Resources;

use App\Customers as Cust;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class FileLinks extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @property integer $link_id
     * @property integer $user_id
     * @property integer $cust_id
     * @property string $cust_name
     * @property string $link_hash
     * @property string $link_name
     * @property string $exp_format
     * @property string $expired
     * @property string $exp_stamp
     * @property string $allow_upload
     * @property integer $file_count
     * @property string $note
     * @property string $expire
     * @property integer $file_link_files_count
     * @return array
     */
    public function toArray($request)
    {
        // return parent::toArray($request);
        return [
            'link_id'      => $this->link_id,
            'user_id'      => $this->user_id,
            'cust_id'      => $this->cust_id,
            'cust_name'    => $this->cust_id ? Cust::find($this->cust_id)->name : 'None',
            'link_hash'    => $this->link_hash,
            'link_name'    => $this->link_name,
            'exp_format'   => Carbon::parse($this->expire)->format('M d, Y'),
            'expired'      => $this->expire < Carbon::now() ? 1 : 0,
            'exp_stamp'    => Carbon::parse($this->expire)->format('Y-m-d'),
            'allow_upload' => $this->allow_upload,
            'file_count'   => isset($this->file_link_files_count) ? $this->file_link_files_count : 0,
            'note'         => $this->note
        ];
    }
}
