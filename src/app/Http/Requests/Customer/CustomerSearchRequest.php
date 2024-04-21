<?php

namespace App\Http\Requests\Customer;

use App\Models\Customer;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;

class CustomerSearchRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user() ? true : false;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'basic' => 'required|boolean',
            'page' => 'required_if:basic,false|numeric',
            'perPage' => 'required_if:basic,false|numeric',
            'searchFor' => 'nullable|string',
        ];
    }

    /**
     * Perform customer search
     */
    public function search()
    {
        if ($this->basic) {
            return $this->basicSearch();
        }

        return $this->detailedSearch();
    }

    /**
     * Basic search with only customer name involved
     */
    protected function basicSearch()
    {
        $searchList = Customer::orderBy('name')
            ->where('name', 'like', '%'.$this->searchFor.'%')
            ->get();

        Log::stack(['daily', 'cust'])
            ->debug('Basic Customer Search Results', $searchList->toArray());

        return $searchList;
    }

    /**
     * More detailed search where customer can search for name, ID, or site name
     */
    protected function detailedSearch()
    {
        $searchList = Customer::orderBy('name')
            ->where('name', 'like', '%'.$this->searchFor.'%')
            ->orWhere('cust_id', 'like', '%'.$this->searchFor.'%')
            ->orWhereHas('CustomerSite', function ($q) {
                $q->where('site_name', 'like', '%'.$this->searchFor.'%')
                    ->orWhere('cust_site_id', 'like', '%'.$this->searchFor.'%');
            })
            ->with('CustomerSite')
            ->paginate($this->perPage);

        Log::stack(['daily', 'cust'])
            ->debug('Detailed Customer Search Results', $searchList->toArray());

        return $searchList;
    }
}
