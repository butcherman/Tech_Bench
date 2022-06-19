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
        // $deleted = [];

        // //  Get deleted Equipment
        // $deleted['equipment'] = CustomerEquipment::where('cust_id', $id)->onlyTrashed()->get()->makeVisible('deleted_at');
        // //  Get deleted Contacts
        // $deleted['contacts'] = CustomerContact::where('cust_id', $id)->onlyTrashed()->get()->makeVisible('deleted_at');
        // //  Get deleted Notes
        // $deleted['notes'] = CustomerNote::where('cust_id', $id)->onlyTrashed()->get()->makeVisible('deleted_at');
        // //  Get deleted files
        // $deleted['files'] = CustomerFile::where('cust_id', $id)->onlyTrashed()->get()->makeVisible('deleted_at');


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

        return $deleted;
    }
}
