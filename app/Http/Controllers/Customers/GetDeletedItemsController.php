<?php

namespace App\Http\Controllers\Customers;

use App\Http\Controllers\Controller;

use App\Models\Customer;
use App\Models\CustomerFile;
use App\Models\CustomerNote;
use App\Models\CustomerContact;
use App\Models\CustomerEquipment;

class GetDeletedItemsController extends Controller
{
    /**
     * Get all items that have been soft deleted for the customer
     */
    public function __invoke($id)
    {
        $this->authorize('manage', Customer::class);

        $deleted = [
            'equipment' => CustomerEquipment::where('cust_id', $id)->onlyTrashed()->get(),
            'contacts'  => CustomerContact::where('cust_id', $id)->onlyTrashed()->get(),
            'notes'     => CustomerNote::where('cust_id', $id)->onlyTrashed()->get(),
            'files'     => CustomerFile::where('cust_id', $id)->onlyTrashed()->get(),
        ];

        $deleted['equipment']->transform(function($item)
        {
            return [
                'item_id'      => $item->cust_equip_id,
                'item_name'    => $item->name,
                'item_deleted' => $item->deleted_at->toFormattedDateString(),
            ];
        });
        $deleted['contacts']->transform(function($item)
        {
            return [
                'item_id'      => $item->cont_id,
                'item_name'    => $item->name,
                'item_deleted' => $item->deleted_at->toFormattedDateString(),
            ];
        });
        $deleted['notes']->transform(function($item)
        {
            return [
                'item_id'      => $item->note_id,
                'item_name'    => $item->subject,
                'item_deleted' => $item->deleted_at->toFormattedDateString(),
            ];
        });
        $deleted['files']->transform(function($item)
        {
            return [
                'item_id'      => $item->cust_file_id,
                'item_name'    => $item->name,
                'item_deleted' => $item->deleted_at->toFormattedDateString(),
            ];
        });

        return $deleted;
    }
}
