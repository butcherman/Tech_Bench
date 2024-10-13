<?php

namespace App\Service\Customer;

use App\Http\Requests\Customer\CustomerSearchRequest;
use App\Models\Customer;

/**
 * Use Scout to search for customer models
 */
class CustomerSearchService
{
    public function __construct(protected CustomerSearchRequest $searchRequest) {}

    public function __invoke()
    {
        if ($this->searchRequest->searchFor) {
            return $this->filteredSearch();
        }

        return Customer::with('CustomerSite')
            ->orderBy('name', 'asc')
            ->paginate($this->searchRequest->perPage);
    }

    protected function filteredSearch()
    {
        return Customer::search($this->searchRequest->searchFor)
            ->paginate($this->searchRequest->perPage);
    }
}
