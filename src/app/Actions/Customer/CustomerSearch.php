<?php

namespace App\Actions\Customer;

use App\Models\Customer;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class CustomerSearch
{
    /**
     * Perform a search for customer.  No search param returns all Models.
     */
    public function __invoke(Collection $searchData): mixed
    {
        if ($searchData->has('cust_id')) {
            return $this->searchById($searchData->get('cust_id'));
        }

        if ($searchData->get('searchFor')) {
            return $this->filteredSearch($searchData);
        }

        return Customer::with('Sites')
            ->orderBy('name', 'asc')
            ->paginate($searchData->get('perPage'));
    }

    /**
     * Use Scout to perform a detailed search.
     */
    protected function filteredSearch(Collection $searchData): LengthAwarePaginator
    {
        return Customer::search($searchData->get('searchFor'))
            ->paginate($searchData->get('perPage'));
    }

    /**
     * Check to see if a Customer ID is currently in use
     */
    protected function searchById(int $id): Customer|false
    {
        $cust = Customer::withTrashed()->find($id);

        if ($cust) {
            return $cust->makeVisible('deleted_at');
        }

        return false;
    }
}
