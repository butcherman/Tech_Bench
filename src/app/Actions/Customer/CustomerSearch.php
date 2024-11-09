<?php

namespace App\Actions\Customer;

use App\Models\Customer;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class CustomerSearch
{
    /**
     * Use Scout to search for Customer Models
     */
    public function __invoke(Collection $searchData): mixed
    {
        if ($searchData->get('searchFor')) {
            return $this->filteredSearch($searchData);
        }

        return Customer::with('CustomerSite')
            ->orderBy('name', 'asc')
            ->paginate($searchData->get('perPage'));
    }

    protected function filteredSearch(Collection $searchData): LengthAwarePaginator
    {
        return Customer::search($searchData->get('searchFor'))
            ->paginate($searchData->get('perPage'));
    }
}
