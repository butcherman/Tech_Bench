<?php

namespace App\Http\Controllers\Customers;

use App\Models\Customer;
use App\Http\Controllers\Controller;
use App\Http\Requests\Customers\CustomerSearchRequest;

use Illuminate\Support\Arr;

class CustomerSearchController extends Controller
{
    protected $perPage;
    protected $sortType;
    protected $sortField;

    /**
     *  Search for a customer
     */
    public function __invoke(CustomerSearchRequest $request)
    {
        //  Pagination parameters
        $this->perPage   = $request->perPage;
        $this->sortField = $request->sortField;
        $this->sortType  = $request->sortType == 'none' ? 'asc' : $request->sortType;

        //  If no search parameters are set, list all customers
        if(Arr::has($request->toArray(), ['city', 'name', 'equipment']) &&
            ($request->city == null &&
             $request->name == null &&
             $request->equipment == null)
            )
        {
            return $this->getAllCustomers();
        }

        //  Search by given search parameters
        return $this->search($request->only(['city', 'name', 'equipment']));
    }

    protected function search($params)
    {
        return Customer::orderBy($this->sortField, $this->sortType)
            //  TODO - Add search by equipment

            //  Search by City
            ->when(isset($params['city']), function($q) use ($params)
            {
                $q->where('city', 'like', '%'.$params['city'].'%');
            })
            //  Search by Name or ID
            ->when(isset($params['name']), function($q) use ($params)
            {
                $q->where('name', 'like', '%'.$params['name'].'%')
                    ->orWhere('cust_id', 'like', '%'.$params['name'].'%')
                    ->orWhere('dba_name', 'like', '%'.$params['name'].'%');
            })
            ->paginate($this->perPage);


    }

    /*
    *   Return pagination list of all customers
    */
    protected function getAllCustomers()
    {
        return Customer::orderBy($this->sortField, $this->sortType)
            ->paginate($this->perPage);
    }
}
