<?php

namespace App\Http\Controllers\Customers;

use Inertia\Inertia;

use App\Models\Customer;
use App\Http\Controllers\Controller;
use App\Http\Requests\Customers\CustIdRequest;

use Illuminate\Support\Facades\Log;
use Illuminate\Database\QueryException;

class CustomerIdController extends Controller
{
    /**
     *  Search parameter to select which ID to change
     */
    public function index()
    {
        $this->authorize('manage', Customer::class);

        return Inertia::render('Customer/changeId');
    }

    /**
     *  Show the Change ID Form
     */
    public function edit($id)
    {
        $this->authorize('manage', Customer::class);

        return Inertia::render('Customer/changeIdForm', [
            'details' => Customer::where('slug', $id)->firstOrFail(),
        ]);
    }

    /**
     *  Submit the Change ID Form
     */
    public function update(CustIdRequest $request, $id)
    {
        $cust = Customer::findOrFail($id);

        try{
            $cust->update($request->only(['cust_id']));
        }
        catch(QueryException $e)
        {
            Log::error('Update Customer ID failed');
            Log::error($e);
            return redirect()->back()->with(['message' => 'Unable to Update Customer ID for linked Customers', 'type' => 'danger']);
        }

        Log::channel('cust')->notice('Customer ID has been updated for Customer '.$cust->name.' from '.$id.' to '.$request->cust_id.' by '.$request->user()->username);
        return redirect()->route('admin.index');
    }
}
