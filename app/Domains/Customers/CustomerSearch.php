<?php

namespace App\Domains\Customers;

use App\Customers;

use Illuminate\Support\Facades\Log;

class CustomerSearch
{
    //  Initialize the search request
    public function search($request)
    {
        $searchData = $this->getSearchFields($request);
        $pagination = $this->getPagination($request);
        $sort       = $this->getSortFields($request);

        //  Determine if the search request is blank - if so, return all customers
        if((array) $searchData)
        {
            $results = $this->searchFor($searchData, $pagination, $sort, true);
        }
        else
        {
            $results = $this->getAllCustomers($pagination, $sort, true);
        }

        Log::debug('Customer Search performed for ', $request->toArray());
        Log::debug('Customer Search Results', $results != null ? $results->toArray() : []);
        return $results;
    }

    //  Search specifically for a customer ID
    public function searchID($id)
    {
        $cust = Customers::find($id);
        Log::debug('Customer search for ID '.$id.' performed.  Results - ', $cust != null ? $cust->toArray() : []);
        return $cust;
    }

    //  Separate the name, city, and equipment id fields from request data
    protected function getSearchFields($request)
    {
        $searchData = [];
        if(isset($request->name))
        {
            $searchData['name'] = $request->name; // explode(' ', $request->name);
        }
        if(isset($request->city))
        {
            $searchData['city'] = $request->city;
        }
        if(isset($request->equipment))
        {
            $searchData['equipment'] = $request->equipment;
        }

        Log::debug('Search Fields pulled from request.  Data - ', $searchData);
        return (object) $searchData;
    }

    //  Separate pagination information from request data
    protected function getPagination($request)
    {
        return (object) ['page' => $request->page, 'perPage' => $request->perPage];
    }

    //  Separate sort information from request data
    protected function getSortFields($request)
    {
        return (object) ['sortField' => $request->sortField, 'sortType' => $request->sortType];
    }

    //  Perform a detailed search based on the name, city, and equipemnt type paramaters
    protected function searchFor($searchData, $pagination, $sort, $includeSystems = false)
    {
        return Customers::orderBy($sort->sortField, $sort->sortType)
            //  Search equipment field
            ->when(isset($searchData->equipment), function($q) use ($searchData)
            {
                $q->whereHas('ParentSystems.SystemTypes', function($qry) use ($searchData)
                {
                    $qry->where('sys_id', $searchData->equipment);
                })
                ->orWhereHas('CustomerSystems.SystemTypes', function($qry) use ($searchData)
                {
                    $qry->where('sys_id', $searchData->equipment);
                });
            })
            //  Search city field
            ->when(isset($searchData->city), function($q) use ($searchData)
            {
                $q->where('city', 'like', '%'.$searchData->city.'%');
            })
            //  Search name field
            ->when(isset($searchData->name), function($q) use ($searchData)
            {
                $q->where('name', 'like', '%'.$searchData->name.'%')
                    ->orWhere('cust_id', 'like', '%'.$searchData->name.'%')
                    ->orWhere('dba_name', 'like', '%'.$searchData->name.'%');
            })
            //  Search by equipment type
            ->when($includeSystems, function($q)
            {
                $q->with('CustomerSystems.SystemTypes')
                    ->with('ParentSystems.SystemTypes');
            })
            ->paginate($pagination->perPage);
    }

    //  No search data present, return all customers
    protected function getAllCustomers($pagination, $sort, $includeSystems = false)
    {
        return Customers::orderBy($sort->sortField, $sort->sortType)
            ->when($includeSystems, function($q) {
                $q->with('CustomerSystems.SystemTypes')
                ->with('ParentSystems.SystemTypes');
            })
            ->paginate($pagination->perPage);
    }
}
