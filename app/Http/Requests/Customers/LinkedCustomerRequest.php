<?php

namespace App\Http\Requests\Customers;

use App\Models\Customer;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class LinkedCustomerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request
     */
    public function authorize()
    {
        return $this->user()->can('manage', Customer::class);
    }

    /**
     * Get the validation rules that apply to the request
     */
    public function rules()
    {
        return [
            'cust_id' => 'required|exists:customers',
            'parent_id' => 'nullable|exists:customers,cust_id|different:cust_id',
            'add' => 'required|boolean',
        ];
    }

    /**
     * Process the add or remove request
     */
    public function processLink()
    {
        $cust = Customer::find($this->cust_id);

        return $this->add ? $this->addLink($cust) : $this->removeLink($cust);
    }

    /**
     * Add a link to a parent customer site
     */
    protected function addLink(Customer $cust)
    {
        //  Make sure that the parent site does not have a parent itself
        $parent = Customer::find($this->parent_id);
        $cust->update([
            'parent_id' => $parent->parent_id === null ? $parent->cust_id : $parent->parent_id,
        ]);

        Log::channel(['daily', 'cust'])->info('Customer '.$cust->name.' has been linked to '.$parent->name.' by '.Auth::user()->username);

        return ['type' => 'success', 'message' => __('cust.linked.set')];
    }

    /**
     * Remove the link to a parent customer site
     */
    protected function removeLink(Customer $cust)
    {
        $cust->update([
            'parent_id' => null,
        ]);

        Log::channel(['daily', 'cust'])->info('Customer '.$cust->name.' link to parent site has been removed by '.Auth::user()->username);

        return ['type' => 'warning', 'message' => __('cust.linked.removed')];
    }
}
