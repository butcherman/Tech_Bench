<?php

namespace App\Http\Controllers\Customer;

use App\Facades\CacheData;
use App\Facades\UserPermissions;
use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\CustomerEquipmentRequest;
use App\Models\Customer;
use App\Models\CustomerEquipment;
use App\Services\Customer\CustomerEquipmentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class CustomerEquipmentController extends Controller
{
    public function __construct(protected CustomerEquipmentService $svc) {}

    /**
     * Show a list of all Equipment assigned to the customer.
     */
    public function index(Request $request, Customer $customer): Response
    {
        return Inertia::render('Customer/Equipment/Index', [
            'alerts' => fn() => $customer->Alerts,
            'availableEquipment' => fn() => CacheData::equipmentCategorySelectBox(),
            'customer' => fn() => $customer,
            'permissions' => fn() => UserPermissions::customerPermissions($request->user()),
            'siteList' => fn() => $customer->Sites->makeVisible(['href']),

            /**
             * Deferred Props
             */
            'groupedEquipmentList' => Inertia::defer(
                fn() => $customer->Equipment
                    ->load('Sites')
                    ->groupBy('equip_name')
                    ->chunk(25)
            ),
        ]);
    }

    /**
     * Save a new piece of equipment to a customer.
     */
    public function store(CustomerEquipmentRequest $request, Customer $customer): RedirectResponse
    {
        $equip = $this->svc
            ->createEquipment($request->safe()->collect(), $customer);

        return back()->with('success', __('cust.equipment.created', [
            'equip' => $equip->equip_name,
        ]));
    }

    /**
     * Show the selected equipment and all relevant data.
     */
    public function show(Request $request, Customer $customer, CustomerEquipment $equipment): Response
    {
        return Inertia::render('Customer/Equipment/Show', [
            'permissions' => fn() => UserPermissions::customerPermissions($request->user()),
            'customer' => fn() => $customer,
            'equipment' => fn() => $equipment,
            'siteList' => fn() => $equipment->Sites->makeVisible(['href']),
            'equipment-data' => fn() => $equipment->CustomerEquipmentData,
            'noteList' => fn() => $equipment->getNotes(),
            'fileList' => fn() => $equipment->getFiles(),
        ]);
    }

    /**
     * Update the list of sites that a piece of equipment belong to.
     */
    public function update(
        CustomerEquipmentRequest $request,
        Customer $customer,
        CustomerEquipment $equipment
    ): RedirectResponse {
        $this->svc->updateEquipmentSites($request->safe()->collect(), $equipment);

        return back()->with('success', __('cust.equipment.site-updated'));
    }

    /**
     * Soft Delete Customer Equipment
     */
    public function destroy(Customer $customer, CustomerEquipment $equipment): RedirectResponse
    {
        $this->authorize('delete', $equipment);

        $name = $equipment->name;

        $this->svc->destroyEquipment($equipment);

        return redirect(route('customers.equipment.index', $customer->slug))
            ->with('warning', 'Equipment Deleted');
    }

    /**
     * Restore Soft deleted equipment
     */
    public function restore(Customer $customer, CustomerEquipment $equipment): RedirectResponse
    {
        $this->authorize('restore', $equipment);

        $this->svc->restoreEquipment($equipment);

        return back()
            ->with('success', __('cust.equipment.restored', [
                'equip' => $equipment->equip_name,
            ]));
    }

    /**
     * Force delete a soft deleted equipment
     */
    public function forceDelete(Customer $customer, CustomerEquipment $equipment): RedirectResponse
    {
        $this->authorize('forceDelete', $equipment);

        $this->svc->destroyEquipment($equipment, true);

        return back()
            ->with('warning', __('cust.equipment.force_deleted', [
                'equip' => $equipment->equip_name,
            ]));
    }
}
