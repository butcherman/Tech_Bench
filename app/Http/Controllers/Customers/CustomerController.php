<?php

namespace App\Http\Controllers\Customers;

use App\Actions\BuildCustomerPermissions;
use App\Actions\EquipmentOptionList;
use App\Http\Controllers\Controller;
use App\Http\Requests\Customers\CustomerRequest;
use App\Http\Requests\Customers\SoftDeletedRequest;
use App\Jobs\CustomerRemoveBookmarksJob;
use App\Models\Customer;
use App\Models\CustomerFileType;
use App\Models\EquipmentCategory;
use App\Models\UserCustomerBookmark;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class CustomerController extends Controller
{
    /**
     * Search page for customers
     */
    public function index(Request $request)
    {
        return Inertia::render('Customers/Index', [
            'permissions' => (new BuildCustomerPermissions)->execute(Customer::class, $request->user()),
            'equipment' => (new EquipmentOptionList)->build(),
        ]);
    }

    /**
     * Show the form for creating a new customer
     */
    public function create()
    {
        $this->authorize('create', Customer::class);

        return Inertia::render('Customers/Create', [
            'select-id' => (bool) config('customer.select_id'),
            'default-state' => config('customer.default_state'),
        ]);
    }

    /**
     * Store a newly created Customer
     */
    public function store(CustomerRequest $request)
    {
        $request->setSlug();
        $newCust = Customer::create($request->only([
            'cust_id', 'parent_id', 'name', 'dba_name', 'address', 'city', 'state', 'zip', 'slug',
        ]));

        Log::stack(['daily', 'cust', 'user'])->info(
            'New Customer create by '.$request->user()->username, $newCust->toArray()
        );

        return redirect(route('customers.show', $newCust->slug))
            ->with('success', __('cust.created', ['name' => $newCust->name]));
    }

    /**
     * Display the specified Customer
     */
    public function show(Customer $customer)
    {
        return Inertia::render('Customers/Show', [
            'permissions' => (new BuildCustomerPermissions)->execute($customer, Auth::user()),
            'is-fav' => (bool) UserCustomerBookmark::where('user_id', Auth::user()->user_id)
                ->where('cust_id', $customer->cust_id)->count(),
            'customer' => fn () => $customer,
            'equipment' => fn () => $customer->ParentEquipment->merge($customer->CustomerEquipment),
            'contacts' => fn () => $customer->ParentContact->merge($customer->CustomerContact),
            'notes' => fn () => $customer->ParentNote->merge($customer->CustomerNote),
            'files' => fn() => $customer->ParentFile->merge($customer->CustomerFile),

            'file-types' => fn() => CustomerFileType::all()->pluck('description'),
            'equip-types' => fn() => EquipmentCategory::with('EquipmentType.DataFieldType')->get(),
        ]);
    }

    /**
     * Update the specified customers details
     */
    public function update(CustomerRequest $request, Customer $customer)
    {
        //  We will only update the customer Slug if it is allowed
        if (config('customer.update_slug')) {
            $request->setSlug();
            $customer->update($request->only(['slug']));
        }

        $customer->update($request->only(['name', 'dba_name', 'address', 'city', 'state', 'zip']));

        Log::stack(['daily', 'cust', 'user'])->info(
            'Customer ID '.$customer->cust_id.' updated by '.$request->user()->username, $customer->toArray()
        );

        return redirect(route('customers.show', $customer->slug))
            ->with('success', __('cust.updated', ['name' => $customer->name]));
    }

    /**
     * Soft Delete a customer
     */
    public function destroy(Customer $customer)
    {
        $this->authorize('delete', $customer);
        $customer->delete();

        //  Remove all bookmarks to avoid errors
        CustomerRemoveBookmarksJob::dispatch($customer);

        Log::stack(['daily', 'cust'])->notice('Customer '.$customer->name.' has been deactivated by '.Auth::user()->username);

        return redirect(route('customers.index'))->with('warning', __('cust.destroy', ['name' => $customer->name]));
    }

    /**
     * Restore a group of previously soft deleted customers
     */
    public function restore(SoftDeletedRequest $request)
    {
        $request->restore();

        return back()->with('success', __('cust.restore'));
    }

    /**
     * Completely delete a group of soft deleted customers
     */
    public function forceDelete(SoftDeletedRequest $request)
    {
        $request->destroy();

        return back()->with('warning', __('cust.delete'));
    }
}
