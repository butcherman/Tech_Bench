<?php

namespace App\Http\Controllers\Customers;

use App\Models\Customer;
use App\Models\CustomerFile;
use App\Models\CustomerNote;
use App\Models\CustomerContact;
use App\Models\CustomerEquipment;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class GetDeletedItemsController extends Controller
{
    /**
     *  Get deleted items for a customer
     */
    public function __invoke($id, Request $request)
    {
        $this->authorize('manage', Customer::class);

        $deleted = [];

        //  Get deleted Equipment
        $deleted['equipment'] = CustomerEquipment::where('cust_id', $id)->onlyTrashed()->get()->makeVisible('deleted_at');

        //  Get deleted Contacts
        $deleted['contacts'] = CustomerContact::where('cust_id', $id)->onlyTrashed()->get()->makeVisible('deleted_at');

        //  Get deleted Notes
        $deleted['notes'] = CustomerNote::where('cust_id', $id)->onlyTrashed()->get()->makeVisible('deleted_at');

        //  Get deleted files
        $deleted['files'] = CustomerFile::where('cust_id', $id)->onlyTrashed()->get()->makeVisible('deleted_at');

        return $deleted;
    }
}
