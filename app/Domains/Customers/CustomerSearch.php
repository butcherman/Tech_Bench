<?php

namespace App\Domains\Customers;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

use App\Customers;
use App\Http\Requests\CustomerSearchRequest;
use App\Http\Resources\CustomersCollection;

class CustomerSearch
{
    //  Search for a customer based on all possible paramaters that can be searched for
    public function searchCustomer(CustomerSearchRequest $request)
    {
        if(isset($request->name) || isset($request->city) || isset($request->system))
        {
            $searchResults = new CustomersCollection(
                Customers::orderBy($request->sortField, $request->sortType)
                    //  Search the name, dba name, and cust id columns
                    ->where(function($query) use ($request) {
                        $query->where('name', 'like', '%'.$request->name.'%')
                            ->orWhere('cust_id', 'like', '%'.$request->name.'%')
                            ->orWhere('dba_name', 'like', '%'.$request->name.'%');
                    })
                    //  Search the city column
                    ->where('city', 'like', '%'.$request->city.'%')
                    //  Include the customers systems
                    ->with('CustomerSystems.SystemTypes')
                    //  If the system field is present - search for system type
                    ->when($request->system, function($query) use ($request) {
                        $query->whereHas('CustomerSystems.SystemTypes', function($query) use ($request) {
                            $query->where('sys_id', $request->system);
                        });
                    })
                    ->paginate($request->perPage)
            );
        }
        else
        {
            $searchResults = new CustomersCollection(Customers::orderBy($request->sortField, $request->sortType)->with('CustomerSystems.SystemTypes')->paginate($request->perPage));
        }

        Log::debug('Performing customer search with paramaters - ', array($request));
        Log::debug('Performed customer search.  Results - ', array($searchResults));
        return $searchResults;
    }

    //  Search for a customer ID to see if it is valid
    public function searchCustomerID($id)
    {
        $cust = Customers::find($id);

        Log::debug('Performed Customer search for Customer ID '.$id.'.  Result - ', array($cust));
        return $cust;
    }
}
