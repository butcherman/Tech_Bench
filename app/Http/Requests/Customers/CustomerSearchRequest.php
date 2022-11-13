<?php

namespace App\Http\Requests\Customers;

use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Http\FormRequest;
use App\Models\Customer;

class CustomerSearchRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request
     */
    public function authorize()
    {
        return $this->user() ? true : false;
    }

    /**
     * Get the validation rules that apply to the request
     */
    public function rules()
    {
        return [
            'page'      => 'nullable|numeric',
            'perPage'   => 'required|numeric',
            'sortField' => 'required|string',
            'sortType'  => 'required|string',
            'name'      => 'nullable|string',
            'city'      => 'nullable|string',
            'equipment' => 'nullable|string',
        ];
    }

    /**
     * Check to see if any search paramaters are present
     */
    public function checkSearch()
    {
        if($this->filled('name') || $this->filled('city') || $this->filled('equip'))
        {
            return $this->search();
        }

        return $this->getAllCustomers();
    }

    /**
     * Return a pagination list of all customers
     */
    protected function getAllCustomers()
    {
        Log::debug('Getting all customers');
        $custList =  Customer::orderBy($this->sortField, $this->sortType)
            ->with('CustomerEquipment')
            ->with('ParentEquipment')
            ->paginate($this->perPage);

        Log::debug('Customer List', $custList->toArray());
        return $custList;
    }

    /**
     * Search for customer based on input paramaters and return pagination results
     */
    protected function search()
    {
        $equip = $this->equip;
        $city  = $this->city;
        $name  = $this->name;

        $custList = Customer::orderBy($this->sortField, $this->sortType)
            //  Search by equipment
            ->when($this->filled('equip'), function($q) use ($equip) {
                $q->whereHas('ParentEquipment', function($q2) use ($equip) {
                    $q2->where('name', $equip);
                })
                ->orWhereHas('EquipmentType', function($q2) use ($equip) {
                    $q2->where('name', $equip);
                });
            })
            //  Search by City
            ->when($this->filled('city'), function($q) use ($city) {
                $q->where('city', 'like', '%'.$city.'%');
            })
            //  Search by Name or ID
            ->when($this->filled('name'), function($q) use ($name) {
                $q->where('name', 'like', '%'.$name.'%')
                    ->orWhere('cust_id', 'like', '%'.$name.'%')
                    ->orWhere('dba_name', 'like', '%'.$name.'%');
            })
            ->with('CustomerEquipment')
            ->with('ParentEquipment')
            ->paginate($this->perPage);

        Log::debug('Customer Search Results', $custList->toArray());
        return $custList;
    }
}
