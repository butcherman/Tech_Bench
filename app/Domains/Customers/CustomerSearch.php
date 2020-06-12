<?php

namespace App\Domains\Customers;

use App\Customers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;


class CustomerSearch
{
    //  Initialize the search request
    public function search($request)
    {
        $searchData = $this->getSearchFields($request);
        $pagination = $this->getPagination($request);
        $sort       = $this->getSortFields($request);

        if((array)$searchData)
        {
            $results = $this->searchFor($searchData, $pagination, $sort, true);
        }
        else
        {
            $results = $this->getAllCustomers($pagination, $sort, true);
        }

        return $results;
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
                $q->whereHas('ParentSystems.SystemTypes', function ($qry) use ($searchData)
                {
                    $qry->where('sys_id', $searchData->equipment);
                })
                ->orWhereHas('CustomerSystems.SystemTypes', function ($qry) use ($searchData)
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
            ->when($includeSystems, function($q)
            {
                $q->with('CustomerSystems.SystemTypes')
                ->with('ParentSystems.SystemTypes');
            })
            ->paginate($pagination->perPage);
    }
}
