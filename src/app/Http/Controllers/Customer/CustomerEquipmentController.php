<?php

namespace App\Http\Controllers\Customer;

use App\Actions\BuildCustomerPermissions;
use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\CustomerEquipmentRequest;
use App\Jobs\Customer\CreateCustomerDataFieldsJob;
use App\Models\Customer;
use App\Models\CustomerEquipment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class CustomerEquipmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, Customer $customer)
    {
        return Inertia::render('Customer/Equipment/Index', [
            'permissions' => fn() => BuildCustomerPermissions::build($request->user()),
            'customer' => fn() => $customer,
            'siteList' => fn() => $customer->CustomerSite,
            'alerts' => fn() => $customer->CustomerAlert,
            'equipmentList' => fn() => $customer->CustomerEquipment,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CustomerEquipmentRequest $request, Customer $customer)
    {
        $equip = $request->createEquipment();
        dispatch(new CreateCustomerDataFieldsJob($equip));

        Log::channel('cust')->info('New Customer Equipment added to ' . $customer->name .
            ' by ' . $request->user()->username, $equip->toArray());

        return back()->with('success', __('cust.equipment.created', ['equip' => $equip->equip_name]));
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Customer $customer, CustomerEquipment $equipment)
    {
        return Inertia::render('Customer/Equipment/Show', [
            'permissions' => fn() => BuildCustomerPermissions::build($request->user()),
            'customer' => fn() => $customer,
            'equipment' => fn() => $equipment,
            'siteList' => fn() => $equipment->CustomerSite->makeVisible('href'),
            'equipment-data' => fn() => $equipment->CustomerEquipmentData,
            'notes' => fn() => $equipment->CustomerNote,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CustomerEquipmentRequest $request, Customer $customer, CustomerEquipment $equipment)
    {
        $equipment->CustomerSite()->sync($request->site_list);

        Log::channel('cust')->info('Customer Sites updated for Customer Equipment by ' .
            $request->user()->username, $equipment->toArray());

        return back()->with('success', __('cust.equipment.site-updated'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Customer $customer, CustomerEquipment $equipment)
    {
        $this->authorize('delete', $equipment);

        $equipment->delete();

        Log::channel('cust')->notice('Customer Equipment Disabled for ' . $customer->name .
            ' by ' . $request->user()->username, $equipment->toArray());

        return redirect(route('customers.equipment.index', $customer->slug))
            ->with('warning', __('cust.equipment.deleted', ['equip' => $equipment->equip_name]));
    }
}
